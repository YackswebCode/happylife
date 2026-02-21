<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\VtuProvider;
use App\Models\VtuPlan;
use App\Models\VtuTransaction;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * Process Airtime purchase.
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

        // Check commission wallet balance
        $commissionWallet = Wallet::where('user_id', $user->id)
                            ->where('type', 'commission')
                            ->first();

        if (!$commissionWallet || $commissionWallet->balance < $amount) {
            return back()->with('error', 'Insufficient commission wallet balance.');
        }

        DB::beginTransaction();
        try {
            // Deduct from wallet
            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            // Record wallet transaction
            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => 'Airtime purchase for ' . $request->phone,
                'reference'   => 'VTU-' . strtoupper(Str::random(20)),
                'status'      => 'completed',
            ]);

            // Record VTU transaction
            $transaction = VtuTransaction::create([
                'user_id'      => $user->id,
                'service_type' => 'airtime',
                'provider_id'  => $request->network,
                'phone'        => $request->phone,
                'amount'       => $amount,
                'status'       => 'success', // In real integration, call API first
                'reference'    => 'VTU-' . strtoupper(Str::random(20)),
                'description'  => 'Airtime top-up of ₦' . number_format($amount, 2),
            ]);

            DB::commit();

            return redirect()->route('member.vtu.airtime')
                ->with('success', 'Airtime purchased successfully! ₦' . number_format($amount, 2) . ' sent to ' . $request->phone);
        } catch (\Exception $e) {
            DB::rollBack();
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

        // Check commission wallet balance
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