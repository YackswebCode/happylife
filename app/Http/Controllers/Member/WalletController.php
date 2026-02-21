<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\FundingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
   /**
 * Wallet dashboard – now only displays Shopping wallet.
 */
public function index()
{
    $user = Auth::user();

    // Get or create the shopping wallet
    $shoppingWallet = $user->shoppingWallet;  // uses accessor that auto-creates

    // Recent transactions (you may filter to show only shopping wallet transactions if desired)
    $recentTransactions = WalletTransaction::where('user_id', $user->id)
                            ->with('wallet')
                            ->latest()
                            ->take(10)
                            ->get();

    // Pending funding requests (all types, but we can optionally filter by wallet_type)
    $pendingRequests = FundingRequest::where('user_id', $user->id)
                        ->pending()
                        ->latest()
                        ->take(5)
                        ->get();

    return view('member.wallet.index', compact(
        'shoppingWallet',
        'recentTransactions',
        'pendingRequests'
    ));
}
    

    /**
     * Shopping wallet details.
     */
    public function shopping()
    {
        $user = Auth::user();
        $wallet = $user->shoppingWallet; // auto-created
        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                        ->latest()
                        ->paginate(20);

        return view('member.wallet.shopping', compact('wallet', 'transactions'));
    }

    /**
     * All transactions (filterable).
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $query = WalletTransaction::where('user_id', $user->id)
                    ->with('wallet');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('wallet_type')) {
            $query->whereHas('wallet', fn($q) => $q->where('type', $request->wallet_type));
        }

        $transactions = $query->latest()->paginate(30);
        return view('member.wallet.transactions', compact('transactions'));
    }

    /**
     * Show funding page – now exclusively for shopping wallet.
     */
    public function funding()
    {
        return view('member.wallet.funding');
    }

    /**
     * INIT PAYSTACK – (optional, used by other flows)
     */
    public function initPaystack(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        // This method might be called from elsewhere; we ensure wallet_type = shopping
        return response()->json([
            'public_key' => config('services.paystack.public_key'),
            'email'      => Auth::user()->email,
            'amount'     => $request->amount * 100,
            'reference'  => 'FUND-'.uniqid().'-'.Auth::id(),
            'metadata'   => [
                'user_id'     => Auth::id(),
                'wallet_type' => 'shopping',
            ],
        ]);
    }

    /**
     * INIT FLUTTERWAVE – (optional)
     */
    public function initFlutterwave(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        return response()->json([
            'public_key'    => config('services.flutterwave.public_key'),
            'tx_ref'        => 'FUND-'.uniqid().'-'.Auth::id(),
            'amount'        => $request->amount,
            'currency'      => 'NGN',
            'customer'      => ['email' => Auth::user()->email, 'name' => Auth::user()->name],
            'meta'          => ['user_id' => Auth::id(), 'wallet_type' => 'shopping'],
            'customizations'=> [
                'title'       => 'Fund Shopping Wallet',
                'description' => 'Add money to shopping wallet',
                'logo'        => asset('images/logo.png'),
            ],
        ]);
    }

/**
 * ✅ PAYMENT SUCCESS – called via AJAX after gateway popup.
 * Credits the SHOPPING wallet directly, no gateway verification.
 */
public function paymentSuccess(Request $request)
{
    $request->validate([
        'reference'   => 'required|string',
        'amount'      => 'required|numeric|min:0.01',
        'gateway'     => 'required|in:paystack,flutterwave',
        'wallet_type' => 'sometimes|in:shopping', // enforce shopping
    ]);

    $user = Auth::user();
    $amount = $request->amount;
    $walletType = 'shopping';

    DB::beginTransaction();
    try {
        // ✅ FIX: Use the accessor/property, NOT the relationship method
        $wallet = $user->shoppingWallet;   // <-- this auto-creates if missing

        // If for some reason $wallet is still null, create it manually
        if (!$wallet) {
            $wallet = $user->wallets()->create([
                'type'           => 'shopping',
                'balance'        => 0,
                'locked_balance' => 0,
            ]);
        }

        // Credit the wallet
        $wallet->balance += $amount;
        $wallet->save();

        // Create transaction record
        WalletTransaction::create([
            'wallet_id'   => $wallet->id,
            'user_id'     => $user->id,
            'type'        => 'credit',
            'amount'      => $amount,
            'description' => 'Online funding via ' . ucfirst($request->gateway),
            'reference'   => $request->reference,
            'status'      => 'completed',
            'metadata'    => json_encode([
                'gateway'     => $request->gateway,
                'wallet_type' => $walletType,
            ]),
        ]);

        // ✅ Ensure the funding_requests table has a 'wallet_type' column
        FundingRequest::create([
            'user_id'        => $user->id,
            'wallet_type'    => $walletType,      // requires migration
            'amount'         => $amount,
            'payment_method' => $request->gateway,
            'transaction_id' => $request->reference,
            'status'         => 'approved',       // instant approval for online
            'approved_at'    => now(),
        ]);

        DB::commit();

        return response()->json([
            'success'  => true,
            'message'  => 'Wallet funded successfully! ₦' . number_format($amount, 2) . ' added.',
            'redirect' => route('member.wallet.index'),
        ]);

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        // Check for duplicate transaction reference
        if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry code
            Log::warning('Duplicate transaction reference: ' . $request->reference);
            return response()->json([
                'success' => false,
                'message' => 'This transaction reference has already been used.',
            ], 422);
        }
        // Check for missing column
        if (str_contains($e->getMessage(), 'Unknown column')) {
            Log::critical('Missing wallet_type column in funding_requests table. Run migration.');
            return response()->json([
                'success' => false,
                'message' => 'System configuration error. Please contact support.',
            ], 500);
        }
        throw $e; // rethrow if not handled
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Payment Success Error: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'trace'   => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to credit wallet. Please contact support.',
        ], 500);
    }
}

    /**
     * ✅ BANK TRANSFER REQUEST – store manual funding request for shopping wallet.
     */
    public function requestFunding(Request $request)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:100',
            'transaction_id' => 'required|string|unique:funding_requests,transaction_id',
            'proof'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes'          => 'nullable|string',
            'wallet_type'    => 'required|in:shopping', // must be shopping
        ]);

        $user = Auth::user();

        // Store proof file
        $proofPath = $request->file('proof')->store('funding-proofs', 'public');

        FundingRequest::create([
            'user_id'        => $user->id,
            'wallet_type'    => $request->wallet_type, // 'shopping'
            'amount'         => $request->amount,
            'payment_method' => 'bank_transfer',
            'transaction_id' => $request->transaction_id,
            'proof'          => $proofPath,
            'notes'          => $request->notes,
            'status'         => 'pending',
        ]);

        return redirect()->route('member.wallet.funding')
            ->with('success', 'Funding request submitted successfully! It will be reviewed within 24 hours.');
    }
}