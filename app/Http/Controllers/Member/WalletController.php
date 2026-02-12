<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\FundingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    /**
     * Show wallet dashboard (overview of all wallets).
     */
    public function index()
    {
        $user = Auth::user();

        $wallets = Wallet::where('user_id', $user->id)
                        ->get()
                        ->keyBy('type');

        // Only keep active wallet types (rank removed)
        $types = ['commission', 'registration', 'shopping'];
        foreach ($types as $type) {
            if (!isset($wallets[$type])) {
                $wallets[$type] = Wallet::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'balance' => 0,
                    'locked_balance' => 0,
                ]);
            }
        }

        // Recent transactions
        $recentTransactions = WalletTransaction::where('user_id', $user->id)
                                ->with('wallet')
                                ->latest()
                                ->take(10)
                                ->get();

        // Pending funding requests
        $pendingRequests = FundingRequest::where('user_id', $user->id)
                                ->where('status', 'pending')
                                ->get();

        return view('member.wallet.index', compact('wallets', 'recentTransactions', 'pendingRequests'));
    }

    /**
     * Commission wallet details.
     */
    public function commission()
    {
        $user = Auth::user();
        $wallet = $this->getOrCreateWallet($user->id, 'commission');
        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                        ->latest()
                        ->paginate(20);

        return view('member.wallet.commission', compact('wallet', 'transactions'));
    }

    /**
     * Shopping wallet details.
     */
    public function shopping()
    {
        $user = Auth::user();
        $wallet = $this->getOrCreateWallet($user->id, 'shopping');
        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                        ->latest()
                        ->paginate(20);

        return view('member.wallet.shopping', compact('wallet', 'transactions'));
    }

    /**
     * All transactions across all wallets.
     */
    public function transactions()
    {
        $user = Auth::user();
        $transactions = WalletTransaction::where('user_id', $user->id)
                        ->with('wallet')
                        ->latest()
                        ->paginate(30);

        return view('member.wallet.transactions', compact('transactions'));
    }

    /**
     * Show funding page (dual gateway UI).
     */
    public function funding()
    {
        $user = Auth::user();
        $registrationWallet = $this->getOrCreateWallet($user->id, 'registration');
        return view('member.wallet.funding', compact('registrationWallet'));
    }

    /**
     * Initialize Paystack payment.
     */
    public function initPaystack(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $user = Auth::user();
        $amount = $request->amount * 100; // kobo
        $reference = 'FUND-' . strtoupper(Str::random(20));

        $paystackSecret = config('services.paystack.secret');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $paystackSecret,
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $user->email,
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => route('member.wallet.index'), // ✅ Direct to wallet dashboard
            'metadata' => [
                'user_id' => $user->id,
                'wallet_type' => 'registration',
            ],
        ]);

        if ($response->successful() && $response['status']) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Unable to initialize Paystack payment.');
    }

    /**
     * Initialize Flutterwave payment.
     */
    public function initFlutterwave(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $reference = 'FUND-' . strtoupper(Str::random(20));

        $flutterwaveSecret = config('services.flutterwave.secret');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $flutterwaveSecret,
            'Content-Type' => 'application/json',
        ])->post('https://api.flutterwave.com/v3/payments', [
            'tx_ref' => $reference,
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' => route('member.wallet.index'), // ✅ Direct to wallet dashboard
            'customer' => [
                'email' => $user->email,
                'name' => $user->name,
            ],
            'customizations' => [
                'title' => 'Fund Registration Wallet',
                'description' => 'Add funds to your Happylife registration wallet',
                'logo' => asset('images/logo.png'),
            ],
            'meta' => [
                'user_id' => $user->id,
                'wallet_type' => 'registration',
            ],
        ]);

        if ($response->successful() && $response['status'] === 'success') {
            return redirect($response['data']['link']);
        }

        return back()->with('error', 'Unable to initialize Flutterwave payment.');
    }

    /**
     * ✅ Process successful payment from frontend callback.
     * No gateway verification – trusts the JS callback.
     */
    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'reference' => 'required|string',
            'amount'    => 'required|numeric|min:100',
            'gateway'   => 'required|in:paystack,flutterwave',
        ]);

        $user = Auth::user();
        $reference = $request->reference;
        $amount = $request->amount;
        $gateway = $request->gateway;

        // Prevent duplicate processing
        $exists = FundingRequest::where('transaction_id', $reference)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This transaction has already been processed.'
            ], 409);
        }

        DB::transaction(function () use ($user, $amount, $reference, $gateway) {
            // Credit registration wallet
            $wallet = $this->getOrCreateWallet($user->id, 'registration');
            $wallet->balance += $amount;
            $wallet->save();

            // Create transaction record
            WalletTransaction::create([
                'wallet_id'   => $wallet->id,
                'user_id'     => $user->id,
                'type'        => WalletTransaction::TYPE_CREDIT,
                'amount'      => $amount,
                'description' => "Wallet funding via {$gateway}",
                'reference'   => $reference,
                'status'      => WalletTransaction::STATUS_COMPLETED,
                'metadata'    => ['gateway' => $gateway],
            ]);

            // Log funding request (auto-approved)
            FundingRequest::create([
                'user_id'         => $user->id,
                'amount'          => $amount,
                'payment_method'  => 'online',
                'transaction_id'  => $reference,
                'status'          => 'approved',
                'approved_at'     => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Wallet funded successfully!',
            'redirect' => route('member.wallet.index')
        ]);
    }

    /**
     * Submit manual funding request (bank transfer with proof).
     */
    public function requestFunding(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:bank_transfer',
            'transaction_id' => 'required|string|unique:funding_requests,transaction_id',
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Upload proof
        $path = $request->file('proof')->store('funding-proofs', 'public');

        FundingRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'proof' => $path,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('member.wallet.index')
            ->with('success', 'Funding request submitted successfully. Awaiting admin approval.');
    }

    /**
     * Helper: get or create wallet by type.
     */
    private function getOrCreateWallet($userId, $type)
    {
        return Wallet::firstOrCreate(
            ['user_id' => $userId, 'type' => $type],
            ['balance' => 0, 'locked_balance' => 0]
        );
    }
}