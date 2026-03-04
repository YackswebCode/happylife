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
     * AJAX: Get data plans.
     */
    public function getPlans(Request $request)
    {
        $request->validate(['network_id' => 'required|exists:vtu_providers,id']);
        $provider = VtuProvider::findOrFail($request->network_id);

        $networkCode = strtolower($provider->code);

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) return response()->json(['error' => 'API not configured'], 500);

        try {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $apiSettings->api_key])
                        ->get(rtrim($apiSettings->base_url, '/') . '/data/lookup?network=' . $networkCode);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch plans'], 500);
            }

            $data = $response->json();
            if (!isset($data['status']) || $data['status'] !== true) {
                return response()->json(['error' => $data['description'] ?? 'Unknown error'], 500);
            }

            $plans = collect($data['message']['details'][0]['plans'] ?? [])->map(function ($plan) {
                return [
                    'plan_code' => $plan['plan_code'],
                    'name'      => $plan['name'],
                    'amount'    => $plan['amount'],
                ];
            })->values();

            return response()->json($plans);

        } catch (\Exception $e) {
            \Log::error('Plan lookup exception: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Process Data purchase.
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
        $amount = $request->amount;
        $phone = $request->phone;
        $providerId = $request->network;
        $planCode = $request->plan_code;

        $provider = VtuProvider::findOrFail($providerId);
        $networkCode = strtolower($provider->code);

        $apiSettings = VtuApiSetting::where('is_active', true)->first();
        if (!$apiSettings) return back()->with('error', 'VTU API not configured.');
        if ($user->commission_wallet_balance < $amount) return back()->with('error', 'Insufficient commission wallet balance.');

        $commissionWallet = Wallet::firstOrCreate(['user_id'=>$user->id,'type'=>'commission'], ['balance'=>$user->commission_wallet_balance,'locked_balance'=>0]);
        $ref = 'VTU-' . strtoupper(Str::random(20));

        try {
            $response = Http::withHeaders([
                'Authorization'=>'Bearer '.$apiSettings->api_key,
                'Content-Type'=>'application/json'
            ])->post(rtrim($apiSettings->base_url,'/').'/data/vend',[
                'plan'=>$planCode,
                'recipient'=>$phone,
                'network'=>$networkCode,
                'ref'=>$ref
            ]);

            if ($response->failed()) throw new \Exception('API request failed');
            $apiData = $response->json();
            if (!isset($apiData['status']) || $apiData['status']!==true) throw new \Exception($apiData['description'] ?? 'Unknown API error');

            DB::beginTransaction();
            $user->commission_wallet_balance -= $amount;
            $user->save();
            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'=>$commissionWallet->id,
                'user_id'=>$user->id,
                'type'=>'debit',
                'amount'=>$amount,
                'description'=>'Data purchase: '.$planCode,
                'reference'=>$ref,
                'status'=>'completed'
            ]);

            VtuTransaction::create([
                'user_id'=>$user->id,
                'service_type'=>'data',
                'provider_id'=>$providerId,
                'phone'=>$phone,
                'amount'=>$amount,
                'status'=>'success',
                'reference'=>$ref,
                'description'=>'Data plan '.$planCode.' for '.$phone,
                'api_response'=>$apiData
            ]);

            DB::commit();

            return redirect()->route('member.vtu.data')->with('success','Data bundle purchased successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Data purchase failed: '.$e->getMessage());
            return back()->with('error','Transaction failed. Please try again.');
        }
    }

    /**
     * Show Electricity payment form.
     */
    public function electricity()
    {
        $discos = VtuProvider::where('category','electricity')
                    ->where('is_active',true)
                    ->get();

        $recentElectricity = VtuTransaction::where('user_id',Auth::id())
                            ->where('service_type','electricity')
                            ->latest()
                            ->take(5)
                            ->get();

        return view('member.vtu.electricity', compact('discos','recentElectricity'));
    }

    /**
     * AJAX: Validate meter number.
     */
    public function validateMeter(Request $request)
    {
        $request->validate([
            'disco_id'=>'required|exists:vtu_providers,id',
            'meter_number'=>'required|string',
            'meter_type'=>'required|in:prepaid,postpaid',
            'amount'=>'required|numeric|min:100',
        ]);

        $provider = VtuProvider::findOrFail($request->disco_id);
        $serviceCode = strtolower($provider->code);

        $apiSettings = VtuApiSetting::where('is_active',true)->first();
        if (!$apiSettings) return response()->json(['error'=>'API not configured'],500);

        try {
            $response = Http::withHeaders([
                'Authorization'=>'Bearer '.$apiSettings->api_key,
                'Content-Type'=>'application/json'
            ])->post(rtrim($apiSettings->base_url,'/').'/electricity/validate',[
                'meter_number'=>$request->meter_number,
                'meter_type'=>$request->meter_type,
                'amount'=>$request->amount,
                'service'=>$serviceCode
            ]);

            if ($response->failed()) return response()->json(['error'=>'Validation request failed'],500);
            $data = $response->json();
            if (!isset($data['status']) || $data['status']!==true) return response()->json(['error'=>$data['description']??'Validation failed'],422);

            $customerName = $data['message']['details']['customer_name'] ?? null;
            $address = $data['message']['details']['address'] ?? null;

            return response()->json(['success'=>true,'customer_name'=>$customerName,'address'=>$address]);

        } catch (\Exception $e) {
            \Log::error('Meter validation exception: '.$e->getMessage());
            return response()->json(['error'=>'Server error'],500);
        }
    }

    /**
     * Process Electricity bill payment.
     */
    public function purchaseElectricity(Request $request)
    {
        $request->validate([
            'disco_id'=>'required|exists:vtu_providers,id',
            'meter_number'=>'required|string',
            'meter_type'=>'required|in:prepaid,postpaid',
            'amount'=>'required|numeric|min:100',
            'customer_name'=>'required|string',
        ]);

        $user = Auth::user();
        $provider = VtuProvider::findOrFail($request->disco_id);
        $serviceCode = strtolower($provider->code);
        $amount = $request->amount;

        if ($user->commission_wallet_balance < $amount) return back()->with('error','Insufficient commission wallet balance.');

        $commissionWallet = Wallet::firstOrCreate(['user_id'=>$user->id,'type'=>'commission'], ['balance'=>$user->commission_wallet_balance]);
        $ref = 'VTU-'.strtoupper(Str::random(20));

        try {
            $response = Http::withHeaders([
                'Authorization'=>'Bearer '.VtuApiSetting::where('is_active',true)->first()->api_key,
                'Content-Type'=>'application/json'
            ])->post(rtrim(VtuApiSetting::where('is_active',true)->first()->base_url,'/').'/electricity/vend',[
                'meter_number'=>$request->meter_number,
                'meter_type'=>$request->meter_type,
                'amount'=>$amount,
                'service'=>$serviceCode,
                'phone'=>$user->phone,
                'customer_name'=>$request->customer_name,
                'ref'=>$ref
            ]);

            if ($response->failed()) throw new \Exception('API request failed');
            $apiData = $response->json();
            if (!isset($apiData['status']) || $apiData['status']!==true) throw new \Exception($apiData['description']??'Unknown API error');

            DB::beginTransaction();
            $user->commission_wallet_balance -= $amount;
            $user->save();
            $commissionWallet->balance -= $amount;
            $commissionWallet->save();

            WalletTransaction::create([
                'wallet_id'=>$commissionWallet->id,
                'user_id'=>$user->id,
                'type'=>'debit',
                'amount'=>$amount,
                'description'=>'Electricity bill for '.$request->meter_number,
                'reference'=>$ref,
                'status'=>'completed'
            ]);

            VtuTransaction::create([
                'user_id'=>$user->id,
                'service_type'=>'electricity',
                'provider_id'=>$provider->id,
                'meter_number'=>$request->meter_number,
                'amount'=>$amount,
                'status'=>'success',
                'reference'=>$ref,
                'description'=>$provider->name.' bill payment for '.$request->meter_number,
                'api_response'=>$apiData
            ]);

            DB::commit();

            return redirect()->route('member.vtu.electricity')->with('success','Electricity bill paid successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Electricity purchase failed: '.$e->getMessage());
            return back()->with('error','Transaction failed. Please try again.');
        }
    }
}