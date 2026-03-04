<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\VtuProvider;
use App\Models\VtuPlan;
use App\Models\VtuTransaction;
use App\Models\VtuApiSetting;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VTUController extends Controller
{
    /**
     * VTU Dashboard – show service cards & recent transactions.
     */
    public function index()
    {
        $recentTransactions = VtuTransaction::where('user_id', Auth::id())
                                ->latest()
                                ->take(10)
                                ->get();

        return view('member.vtu.index', compact('recentTransactions'));
    }

    /**
     * Show Airtime purchase form.
     */
    public function airtime()
    {
        $networks = VtuProvider::where('category', 'airtime')
                    ->where('is_active', true)
                    ->get();

        $recentAirtime = VtuTransaction::where('user_id', Auth::id())
                            ->where('service_type', 'airtime')
                            ->latest()
                            ->take(5)
                            ->get();

        return view('member.vtu.airtime', compact('networks', 'recentAirtime'));
    }

    /**
     * Process Airtime purchase with Payscribe API.
     */
  public function purchaseAirtime(Request $request)
{
    $request->validate([
        'network' => 'required|exists:vtu_providers,id',
        'phone'   => 'required|string|min:10|max:15',
        'amount'  => 'required|numeric|min:50',
    ]);

    $user = Auth::user();
    $amount = $request->amount;
    $phone = $request->phone;
    $providerId = $request->network;

    // Get the network provider
    $provider = VtuProvider::findOrFail($providerId);
    // Convert provider name to lowercase for API network code (assume names: MTN, GLO, Airtel, 9mobile)
    $networkCode = strtolower($provider->name);

    // Get active API settings
    $apiSettings = VtuApiSetting::where('is_active', true)->first();
    if (!$apiSettings) {
        return back()->with('error', 'VTU API not configured. Please contact admin.');
    }

    // Check user's commission wallet balance (source of truth)
    if ($user->commission_wallet_balance < $amount) {
        return back()->with('error', 'Insufficient commission wallet balance.');
    }

    // Get or create the commission wallet record in the wallets table
    $commissionWallet = Wallet::firstOrCreate(
        ['user_id' => $user->id, 'type' => 'commission'],
        ['balance' => $user->commission_wallet_balance, 'locked_balance' => 0]
    );

    // Generate a unique reference for both our system and the API
    $ref = 'VTU-' . strtoupper(Str::random(20));

    // Prepare API request
    $baseUrl = rtrim($apiSettings->base_url, '/');
    $url = $baseUrl . '/airtime';
    $apiKey = $apiSettings->api_key;

    $payload = [
        'network'   => $networkCode,
        'amount'    => $amount,
        'recipient' => $phone,
        'ported'    => false,
        'ref'       => $ref,
    ];

    // Call Payscribe API
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post($url, $payload);

        // Check for HTTP errors
        if ($response->failed()) {
            throw new \Exception('API request failed with status ' . $response->status());
        }

        $apiData = $response->json();

        // Check API business status
        if (!isset($apiData['status']) || $apiData['status'] !== true) {
            $errorMsg = $apiData['description'] ?? 'Unknown API error';
            throw new \Exception('API error: ' . $errorMsg);
        }

        // API succeeded – start database transaction
        DB::beginTransaction();

        // Deduct from user's commission wallet balance
        $user->commission_wallet_balance -= $amount;
        $user->save();

        // Deduct from the wallets table record
        $commissionWallet->balance -= $amount;
        $commissionWallet->save();

        // Record wallet transaction
        WalletTransaction::create([
            'wallet_id'   => $commissionWallet->id,
            'user_id'     => $user->id,
            'type'        => 'debit',
            'amount'      => $amount,
            'description' => 'Airtime purchase for ' . $phone,
            'reference'   => $ref,
            'status'      => 'completed',
        ]);

        // Record VTU transaction with API response
        VtuTransaction::create([
            'user_id'      => $user->id,
            'service_type' => 'airtime',
            'provider_id'  => $providerId,
            'phone'        => $phone,
            'amount'       => $amount,
            'status'       => 'success',
            'reference'    => $ref,
            'description'  => 'Airtime top-up of ₦' . number_format($amount, 2),
            'api_response' => $apiData,
        ]);

        DB::commit();

        return redirect()->route('member.vtu.airtime')
            ->with('success', 'Airtime purchased successfully! ₦' . number_format($amount, 2) . ' sent to ' . $phone);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Airtime purchase failed: ' . $e->getMessage());
        return back()->with('error', 'Transaction failed. Please try again.');
    }
}

    /**
     * Show Data purchase form.
     */
    public function data()
    {
        $networks = VtuProvider::where('category', 'data')
                    ->where('is_active', true)
                    ->get();

        $recentData = VtuTransaction::where('user_id', Auth::id())
                        ->where('service_type', 'data')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('member.vtu.data', compact('networks', 'recentData'));
    }

    /**
     * Process Data purchase.
     */
    public function purchaseData(Request $request)
    {
        $request->validate([
            'network' => 'required|exists:vtu_providers,id',
            'phone'   => 'required|string|min:10|max:15',
            'plan_id' => 'required|exists:vtu_plans,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $plan = VtuPlan::findOrFail($request->plan_id);

        $commissionWallet = Wallet::where('user_id', $user->id)
                            ->where('type', 'commission')
                            ->first();

        if (!$commissionWallet || $commissionWallet->balance < $amount) {
            return back()->with('error', 'Insufficient commission wallet balance.');
        }

        DB::beginTransaction();
        try {
            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => 'Data purchase: ' . $plan->name,
                'reference'   => 'VTU-' . strtoupper(Str::random(20)),
                'status'      => 'completed',
            ]);

            VtuTransaction::create([
                'user_id'      => $user->id,
                'service_type' => 'data',
                'provider_id'  => $request->network,
                'plan_id'      => $plan->id,
                'phone'        => $request->phone,
                'amount'       => $amount,
                'status'       => 'success',
                'reference'    => 'VTU-' . strtoupper(Str::random(20)),
                'description'  => $plan->name . ' - ' . $request->phone,
            ]);

            DB::commit();

            return redirect()->route('member.vtu.data')
                ->with('success', 'Data bundle purchased successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaction failed. Please try again.');
        }
    }

    /**
     * Show Cable TV subscription form.
     */
    public function cable()
    {
        $cableProviders = VtuProvider::where('category', 'cable')
                        ->where('is_active', true)
                        ->get();

        $recentCable = VtuTransaction::where('user_id', Auth::id())
                        ->where('service_type', 'cable')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('member.vtu.cable', compact('cableProviders', 'recentCable'));
    }

    /**
     * Process Cable TV subscription.
     */
    public function purchaseCable(Request $request)
    {
        $request->validate([
            'provider'  => 'required|exists:vtu_providers,id',
            'smart_card' => 'required|string',
            'plan_id'   => 'required|exists:vtu_plans,id',
            'amount'    => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $plan = VtuPlan::findOrFail($request->plan_id);
        $provider = VtuProvider::findOrFail($request->provider);

        $commissionWallet = Wallet::where('user_id', $user->id)
                            ->where('type', 'commission')
                            ->first();

        if (!$commissionWallet || $commissionWallet->balance < $amount) {
            return back()->with('error', 'Insufficient commission wallet balance.');
        }

        DB::beginTransaction();
        try {
            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => 'Cable subscription: ' . $provider->name . ' - ' . $plan->name,
                'reference'   => 'VTU-' . strtoupper(Str::random(20)),
                'status'      => 'completed',
            ]);

            VtuTransaction::create([
                'user_id'      => $user->id,
                'service_type' => 'cable',
                'provider_id'  => $request->provider,
                'plan_id'      => $plan->id,
                'smart_card'   => $request->smart_card,
                'amount'       => $amount,
                'status'       => 'success',
                'reference'    => 'VTU-' . strtoupper(Str::random(20)),
                'description'  => $provider->name . ' - ' . $plan->name,
            ]);

            DB::commit();

            return redirect()->route('member.vtu.cable')
                ->with('success', 'Cable TV subscription successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaction failed. Please try again.');
        }
    }

    /**
     * Show Electricity payment form.
     */
    public function electricity()
    {
        $discos = VtuProvider::where('category', 'electricity')
                    ->where('is_active', true)
                    ->get();

        $recentElectricity = VtuTransaction::where('user_id', Auth::id())
                            ->where('service_type', 'electricity')
                            ->latest()
                            ->take(5)
                            ->get();

        return view('member.vtu.electricity', compact('discos', 'recentElectricity'));
    }

    /**
     * Process Electricity bill payment.
     */
    public function purchaseElectricity(Request $request)
    {
        $request->validate([
            'disco'       => 'required|exists:vtu_providers,id',
            'meter_number' => 'required|string',
            'meter_type'  => 'required|in:prepaid,postpaid',
            'amount'      => 'required|numeric|min:100',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        $commissionWallet = Wallet::where('user_id', $user->id)
                            ->where('type', 'commission')
                            ->first();

        if (!$commissionWallet || $commissionWallet->balance < $amount) {
            return back()->with('error', 'Insufficient commission wallet balance.');
        }

        DB::beginTransaction();
        try {
            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => 'Electricity bill payment - Meter: ' . $request->meter_number,
                'reference'   => 'VTU-' . strtoupper(Str::random(20)),
                'status'      => 'completed',
            ]);

            VtuTransaction::create([
                'user_id'      => $user->id,
                'service_type' => 'electricity',
                'provider_id'  => $request->disco,
                'meter_number' => $request->meter_number,
                'amount'       => $amount,
                'status'       => 'success',
                'reference'    => 'VTU-' . strtoupper(Str::random(20)),
                'description'  => 'Electricity bill - ₦' . number_format($amount, 2),
            ]);

            DB::commit();

            return redirect()->route('member.vtu.electricity')
                ->with('success', 'Electricity bill payment successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaction failed. Please try again.');
        }
    }

    /**
     * AJAX: Get data plans for a selected network.
     */
    public function getPlans(Request $request)
    {
        $request->validate(['network_id' => 'required|exists:vtu_providers,id']);

        $plans = VtuPlan::where('provider_id', $request->network_id)
                ->where('is_active', true)
                ->orderBy('amount')
                ->get(['id', 'name', 'amount', 'validity', 'size']);

        return response()->json($plans);
    }

    /**
     * AJAX: Get cable bouquets for a selected provider.
     */
    public function getCablePlans(Request $request)
    {
        $request->validate(['provider_id' => 'required|exists:vtu_providers,id']);

        $plans = VtuPlan::where('provider_id', $request->provider_id)
                ->where('is_active', true)
                ->orderBy('amount')
                ->get(['id', 'name', 'amount']);

        return response()->json($plans);
    }
}