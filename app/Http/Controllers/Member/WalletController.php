<?php
// app/Http/Controllers/Member/WalletController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
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

        // Ensure all wallet types exist (create if not)
        $types = ['commission', 'registration', 'rank', 'shopping'];
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

        // Recent transactions across all wallets
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
     * Rank wallet details.
     */
    public function rank()
    {
        $user = Auth::user();
        $wallet = $this->getOrCreateWallet($user->id, 'rank');
        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                        ->latest()
                        ->paginate(20);

        return view('member.wallet.rank', compact('wallet', 'transactions'));
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
     * Show funding page (form for deposit).
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
    public function initPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100', // minimum ₦100
        ]);

        $user = Auth::user();
        $amount = $request->amount * 100; // Paystack uses kobo

        $reference = 'FUND-' . strtoupper(Str::random(20));

        // Store transaction reference in session or temp record
        session(['payment_reference' => $reference, 'payment_amount' => $request->amount]);

        $paystackSecret = config('services.paystack.secret');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $paystackSecret,
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $user->email,
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => route('member.wallet.verify-payment'),
            'metadata' => [
                'user_id' => $user->id,
                'wallet_type' => 'registration', // funding goes to registration wallet
            ],
        ]);

        if ($response->successful() && $response['status']) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Unable to initialize payment. Please try again.');
    }

    /**
     * Verify Paystack payment and credit registration wallet.
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->reference ?? session('payment_reference');

        if (!$reference) {
            return redirect()->route('member.wallet.funding')->with('error', 'Invalid payment reference.');
        }

        $paystackSecret = config('services.paystack.secret');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $paystackSecret,
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response['status'] && $response['data']['status'] === 'success') {
            $amount = $response['data']['amount'] / 100; // convert back to Naira
            $user = Auth::user() ?? User::find($response['data']['metadata']['user_id']);

            DB::transaction(function () use ($user, $amount, $reference) {
                // Credit registration wallet
                $wallet = $this->getOrCreateWallet($user->id, 'registration');
                $wallet->balance += $amount;
                $wallet->save();

                // Create transaction record
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'user_id' => $user->id,
                    'type' => WalletTransaction::TYPE_CREDIT,
                    'amount' => $amount,
                    'description' => 'Wallet funding via Paystack',
                    'reference' => $reference,
                    'status' => WalletTransaction::STATUS_COMPLETED,
                    'metadata' => [
                        'gateway' => 'paystack',
                        'channel' => $response['data']['channel'] ?? null,
                    ],
                ]);

                // Log funding request (optional)
                FundingRequest::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'payment_method' => 'online',
                    'transaction_id' => $reference,
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
            });

            return redirect()->route('member.wallet.index')
                ->with('success', 'Your wallet has been funded with ₦' . number_format($amount, 2));
        }

        return redirect()->route('member.wallet.funding')->with('error', 'Payment verification failed.');
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
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $userId, 'type' => $type],
            ['balance' => 0, 'locked_balance' => 0]
        );
        return $wallet;
    }
}