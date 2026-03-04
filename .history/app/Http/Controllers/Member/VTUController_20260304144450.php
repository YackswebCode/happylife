<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\VtuProvider;
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

        $provider = VtuProvider::findOrFail($providerId);

        $networkCode = strtolower($provider->code);

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) {
            return back()->with('error', 'VTU API not configured.');
        }

        if ($user->commission_wallet_balance < $amount) {
            return back()->with('error', 'Insufficient commission wallet balance.');
        }

        $commissionWallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'type' => 'commission'],
            ['balance' => $user->commission_wallet_balance, 'locked_balance' => 0]
        );

        $ref = 'VTU-' . strtoupper(Str::random(20));
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

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($url, $payload);

            if ($response->failed()) {
                throw new \Exception('API request failed with status ' . $response->status());
            }

            $apiData = $response->json();

            if (!isset($apiData['status']) || $apiData['status'] !== true) {
                $errorMsg = $apiData['description'] ?? 'Unknown API error';
                throw new \Exception('API error: ' . $errorMsg);
            }

            DB::beginTransaction();

            $user->commission_wallet_balance -= $amount;
            $user->save();

            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => 'Airtime purchase for ' . $phone,
                'reference'   => $ref,
                'status'      => 'completed',
            ]);

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
     * AJAX: Get data plans for a selected network from Payscribe.
     */
    public function getPlans(Request $request)
    {
        $request->validate(['network_id' => 'required|exists:vtu_providers,id']);

        $provider = VtuProvider::findOrFail($request->network_id);

        $networkCode = match($provider->code) {
            'MTN-DATA'  => 'mtn',
            'GLO-DATA'  => 'glo',
            'AIR-DATA'  => 'airtel',
            'ETI-DATA'  => '9mobile',
            default     => strtolower($provider->code),
        };

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) {
            \Log::error('No active VTU API settings found');
            return response()->json(['error' => 'API not configured'], 500);
        }

        $baseUrl = rtrim($apiSettings->base_url, '/');
        $url = $baseUrl . '/data/lookup?network=' . $networkCode;
        $apiKey = $apiSettings->api_key;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get($url);

            if ($response->failed()) {
                \Log::error('Payscribe API error', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
                return response()->json(['error' => 'Failed to fetch plans from provider'], 500);
            }

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== true) {
                $errorMsg = $data['description'] ?? 'Unknown error';
                return response()->json(['error' => $errorMsg], 500);
            }

            $plans = [];
            if (isset($data['message']['details'][0]['plans'])) {
                $plans = collect($data['message']['details'][0]['plans'])->map(function ($plan) {
                    return [
                        'plan_code' => $plan['plan_code'],
                        'name'      => $plan['name'],
                        'amount'    => $plan['amount'],
                    ];
                })->values();
            }

            return response()->json($plans);

        } catch (\Exception $e) {
            \Log::error('Plan lookup exception: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Process Data purchase with Payscribe API.
     */
    public function purchaseData(Request $request)
    {
        $request->validate([
            'network'   => 'required|exists:vtu_providers,id',
            'phone'     => 'required|string|min:10|max:15',
            'plan_code' => 'required|string',
            'amount'    => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $phone = $request->phone;
        $planCode = $request->plan_code;
        $amount = $request->amount;
        $providerId = $request->network;

        $provider = VtuProvider::findOrFail($providerId);

        $networkCode = match($provider->code) {
            'MTN-DATA'  => 'mtn',
            'GLO-DATA'  => 'glo',
            'AIR-DATA'  => 'airtel',
            'ETI-DATA'  => '9mobile',
            default     => strtolower($provider->code),
        };

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) {
            return back()->with('error', 'VTU API not configured.');
        }

        if ($user->commission_wallet_balance < $amount) {
            return back()->with('error', 'Insufficient commission wallet balance.');
        }

        $commissionWallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'type' => 'commission'],
            ['balance' => $user->commission_wallet_balance, 'locked_balance' => 0]
        );

        $ref = 'VTU-' . strtoupper(Str::random(20));
        $baseUrl = rtrim($apiSettings->base_url, '/');
        $url = $baseUrl . '/data/vend';
        $apiKey = $apiSettings->api_key;

        $payload = [
            'plan'      => $planCode,
            'recipient' => $phone,
            'network'   => $networkCode,
            'ref'       => $ref,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($url, $payload);

            if ($response->failed()) {
                throw new \Exception('API request failed with status ' . $response->status());
            }

            $apiData = $response->json();

            if (!isset($apiData['status']) || $apiData['status'] !== true) {
                $errorMsg = $apiData['description'] ?? 'Unknown API error';
                throw new \Exception('API error: ' . $errorMsg);
            }

            DB::beginTransaction();

            $user->commission_wallet_balance -= $amount;
            $user->save();

            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => 'Data purchase: ' . $planCode,
                'reference'   => $ref,
                'status'      => 'completed',
            ]);

            VtuTransaction::create([
                'user_id'      => $user->id,
                'service_type' => 'data',
                'provider_id'  => $providerId,
                'phone'        => $phone,
                'amount'       => $amount,
                'status'       => 'success',
                'reference'    => $ref,
                'description'  => 'Data plan ' . $planCode . ' for ' . $phone,
                'api_response' => $apiData,
            ]);

            DB::commit();

            return redirect()->route('member.vtu.data')
                ->with('success', 'Data bundle purchased successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Data purchase failed: ' . $e->getMessage());
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
     * AJAX: Get cable bouquets for a selected provider.
     */
    public function getCablePlans(Request $request)
    {
        $request->validate(['provider_id' => 'required|exists:vtu_providers,id']);

        $provider = VtuProvider::findOrFail($request->provider_id);
        $serviceCode = $provider->service_code ?? strtolower($provider->name);

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) {
            return response()->json(['error' => 'API not configured'], 500);
        }

        $baseUrl = rtrim($apiSettings->base_url, '/');
        $url = $baseUrl . '/bouquets?service=' . $serviceCode;
        $apiKey = $apiSettings->api_key;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get($url);

            if ($response->failed()) {
                \Log::error('Payscribe API error', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch bouquets'], 500);
            }

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== true) {
                return response()->json(['error' => $data['description'] ?? 'Unknown error'], 500);
            }

            $plans = [];
            if (isset($data['message']['details'])) {
                $plans = collect($data['message']['details'])->map(function ($plan) {
                    return [
                        'id'     => $plan['id'],          
                        'name'   => $plan['name'],
                        'amount' => $plan['amount'],
                    ];
                })->values();
            }

            return response()->json($plans);

        } catch (\Exception $e) {
            \Log::error('Cable plan lookup exception: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * AJAX: Validate smart card / IUC number.
     */
    public function validateSmartCard(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:vtu_providers,id',
            'smart_card'  => 'required|string',
            'plan_id'     => 'required|string',
        ]);

        $provider = VtuProvider::findOrFail($request->provider_id);
        $serviceCode = $provider->service_code ?? strtolower($provider->name);

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) {
            return response()->json(['error' => 'API not configured'], 500);
        }

        $baseUrl = rtrim($apiSettings->base_url, '/');
        $url = $baseUrl . '/multichoice/validate';
        $apiKey = $apiSettings->api_key;

        $payload = [
            'service'  => $serviceCode,
            'account'  => $request->smart_card,
            'month'    => 1,
            'plan_id'  => $request->plan_id,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($url, $payload);

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== true) {
                return response()->json(['error' => $data['description'] ?? 'Validation failed'], 422);
            }

            $customerName = $data['message']['details']['customer_name'] ?? null;

            return response()->json([
                'success'       => true,
                'customer_name' => $customerName,
            ]);

        } catch (\Exception $e) {
            \Log::error('Smart card validation exception: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Process Cable TV subscription.
     */
    public function purchaseCable(Request $request)
    {
        $request->validate([
            'provider'      => 'required|exists:vtu_providers,id',
            'smart_card'    => 'required|string',
            'plan_id'       => 'required|string',
            'amount'        => 'required|numeric|min:1',
            'customer_name' => 'required|string',
        ]);

        $user = Auth::user();
        $provider = VtuProvider::findOrFail($request->provider);
        $serviceCode = $provider->service_code ?? strtolower($provider->name);
        $amount = $request->amount;
        $smartCard = $request->smart_card;
        $planId = $request->plan_id;
        $customerName = $request->customer_name;

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) return back()->with('error', 'VTU API not configured.');
        if ($user->commission_wallet_balance < $amount) return back()->with('error', 'Insufficient commission wallet balance.');

        $commissionWallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'type' => 'commission'],
            ['balance' => $user->commission_wallet_balance]
        );

        $ref = 'VTU-' . strtoupper(Str::random(20));
        $baseUrl = rtrim($apiSettings->base_url, '/');
        $url = $baseUrl . '/multichoice/vend';
        $apiKey = $apiSettings->api_key;

        $payload = [
            'plan_id'       => $planId,
            'customer_name' => $customerName,
            'account'       => $smartCard,
            'service'       => $serviceCode,
            'ref'           => $ref,
            'phone'         => $user->phone,
            'email'         => $user->email,
            'month'         => 1,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($url, $payload);

            if ($response->failed()) {
                throw new \Exception('API request failed with status ' . $response->status());
            }

            $apiData = $response->json();

            if (!isset($apiData['status']) || $apiData['status'] !== true) {
                $errorMsg = $apiData['description'] ?? 'Unknown API error';
                throw new \Exception('API error: ' . $errorMsg);
            }

            DB::beginTransaction();

            $user->commission_wallet_balance -= $amount;
            $user->save();

            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $amount,
                'description' => 'Cable subscription for ' . $smartCard,
                'reference'   => $ref,
                'status'      => 'completed',
            ]);

            VtuTransaction::create([
                'user_id'      => $user->id,
                'service_type' => 'cable',
                'provider_id'  => $provider->id,
                'smart_card'   => $smartCard,
                'amount'       => $amount,
                'status'       => 'success',
                'reference'    => $ref,
                'description'  => $provider->name . ' subscription for ' . $smartCard,
                'api_response' => $apiData,
            ]);

            DB::commit();

            return redirect()->route('member.vtu.cable')
                ->with('success', 'Cable subscription successful!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cable purchase failed: ' . $e->getMessage());
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
 * AJAX: Validate meter number.
 */
public function validateMeter(Request $request)
{
    $request->validate([
        'disco_id'     => 'required|exists:vtu_providers,id',
        'meter_number' => 'required|string',
        'meter_type'   => 'required|in:prepaid,postpaid',
        'amount'       => 'required|numeric|min:100',
    ]);

    $provider = VtuProvider::findOrFail($request->disco_id);

    // Use service_code if available, otherwise fallback to lowercase name
   $serviceCode = $provider->code;

    $apiSettings = VtuApiSetting::where('is_active', true)->first();
    if (!$apiSettings) {
        \Log::error('Electricity Validation: API not configured');
        return response()->json(['error' => 'API not configured'], 500);
    }

    $baseUrl = rtrim($apiSettings->base_url, '/');
    $url     = $baseUrl . '/electricity/validate';
    $apiKey  = $apiSettings->api_key;

    $payload = [
        'meter_number' => $request->meter_number,
        'meter_type'   => $request->meter_type,
        'amount'       => $request->amount,
        'service'      => $serviceCode,
    ];

    try {

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])
            ->post($url, $payload);

        $statusCode = $response->status();
        $rawBody    = $response->body();
        $data       = $response->json();

        /*
        |--------------------------------------------------------------------------
        | LOG HTTP FAILURE (4xx / 5xx)
        |--------------------------------------------------------------------------
        */
        if ($response->failed()) {

            \Log::error('Electricity Validation API HTTP Failure', [
                'http_status'     => $statusCode,
                'url'             => $url,
                'request_payload' => $payload,
                'service_code'    => $serviceCode,
                'meter_number'    => $request->meter_number,
                'raw_response'    => $rawBody,
                'json_response'   => $data,
            ]);

            $errorMsg = $data['description']
                        ?? $data['error']
                        ?? 'Validation request failed';

            return response()->json(['error' => $errorMsg], $statusCode);
        }

        /*
        |--------------------------------------------------------------------------
        | LOG LOGICAL FAILURE (status !== true)
        |--------------------------------------------------------------------------
        */
        if (!isset($data['status']) || $data['status'] !== true) {

            \Log::error('Electricity Validation API Logical Failure', [
                'http_status'     => $statusCode,
                'url'             => $url,
                'request_payload' => $payload,
                'service_code'    => $serviceCode,
                'meter_number'    => $request->meter_number,
                'json_response'   => $data,
            ]);

            $errorMsg = $data['description']
                        ?? $data['error']
                        ?? 'Validation failed';

            return response()->json(['error' => $errorMsg], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */
        $customerName = $data['message']['details']['customer_name'] ?? null;
        $address      = $data['message']['details']['address'] ?? null;

        \Log::info('Electricity Validation Successful', [
            'meter_number' => $request->meter_number,
            'service_code' => $serviceCode,
            'customer_name'=> $customerName,
        ]);

        return response()->json([
            'success'       => true,
            'customer_name' => $customerName,
            'address'       => $address,
        ]);

    } catch (\Exception $e) {

        \Log::error('Electricity Validation Exception', [
            'exception_message' => $e->getMessage(),
            'url'               => $url,
            'request_payload'   => $payload,
            'service_code'      => $serviceCode,
            'meter_number'      => $request->meter_number,
        ]);

        return response()->json(['error' => 'Server error'], 500);
    }
}
/**
 * Process Electricity bill payment.
 */
public function purchaseElectricity(Request $request)
{
    $request->validate([
        'disco_id'      => 'required|exists:vtu_providers,id',
        'meter_number'  => 'required|string',
        'meter_type'    => 'required|in:prepaid,postpaid',
        'amount'        => 'required|numeric|min:100',
        'customer_name' => 'required|string',
    ]);

    $user = Auth::user();
    $provider = VtuProvider::findOrFail($request->disco_id);
    $serviceCode = $provider->service_code ?? strtolower($provider->name);
    $amount = $request->amount;
    $meterNumber = $request->meter_number;
    $meterType = $request->meter_type;
    $customerName = $request->customer_name;

    $apiSettings = VtuApiSetting::where('is_active', true)->first();
    if (!$apiSettings) {
        return back()->with('error', 'VTU API not configured.');
    }

    if ($user->commission_wallet_balance < $amount) {
        return back()->with('error', 'Insufficient commission wallet balance.');
    }

    $commissionWallet = Wallet::firstOrCreate(
        ['user_id' => $user->id, 'type' => 'commission'],
        ['balance' => $user->commission_wallet_balance]
    );

    $ref = 'VTU-' . strtoupper(Str::random(20));
    $baseUrl = rtrim($apiSettings->base_url, '/');
    $url = $baseUrl . '/electricity/vend';
    $apiKey = $apiSettings->api_key;

    $payload = [
        'meter_number'  => $meterNumber,
        'meter_type'    => $meterType,
        'amount'        => $amount,
        'service'       => $serviceCode,
        'phone'         => $user->phone,
        'customer_name' => $customerName,
        'ref'           => $ref,
    ];

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post($url, $payload);

        if ($response->failed()) {
            $apiData = $response->json();
            $errorMsg = $apiData['description'] ?? 'API request failed';
            throw new \Exception($errorMsg);
        }

        $apiData = $response->json();

        if (!isset($apiData['status']) || $apiData['status'] !== true) {
            $errorMsg = $apiData['description'] ?? 'Unknown API error';
            throw new \Exception($errorMsg);
        }

        DB::beginTransaction();

        $user->commission_wallet_balance -= $amount;
        $user->save();

        $commissionWallet->balance -= $amount;
        $commissionWallet->save();

        WalletTransaction::create([
            'wallet_id'   => $commissionWallet->id,
            'user_id'     => $user->id,
            'type'        => 'debit',
            'amount'      => $amount,
            'description' => 'Electricity bill for ' . $meterNumber,
            'reference'   => $ref,
            'status'      => 'completed',
        ]);

        VtuTransaction::create([
            'user_id'      => $user->id,
            'service_type' => 'electricity',
            'provider_id'  => $provider->id,
            'meter_number' => $meterNumber,
            'amount'       => $amount,
            'status'       => 'success',
            'reference'    => $ref,
            'description'  => $provider->name . ' bill payment for ' . $meterNumber,
            'api_response' => $apiData,
        ]);

        DB::commit();

        return redirect()->route('member.vtu.electricity')
            ->with('success', 'Electricity bill paid successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Electricity purchase failed: ' . $e->getMessage());
        return back()->with('error', 'Payment failed: ' . $e->getMessage());
    }
}
}