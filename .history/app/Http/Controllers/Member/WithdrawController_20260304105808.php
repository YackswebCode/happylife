<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WithdrawController extends Controller
{
    /**
     * Show withdrawal form & pending request (if any).
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user has approved KYC
        $kycApproved = $user->kycs()->where('status', 'approved')->exists();

        // Get both wallet balances from users table
        $commissionBalance = $user->commission_wallet_balance ?? 0;
        $rankBalance       = $user->rank_wallet_balance ?? 0;

        // Check if there's already a pending withdrawal (regardless of wallet)
        $pendingWithdrawal = Withdrawal::where('user_id', $user->id)
                                ->where('status', 'pending')
                                ->first();

        // Get recent withdrawals (last 5)
        $recentWithdrawals = Withdrawal::where('user_id', $user->id)
                                ->latest()
                                ->take(5)
                                ->get();

        return view('member.withdrawals.index', compact(
            'commissionBalance',
            'rankBalance',
            'pendingWithdrawal',
            'recentWithdrawals',
            'kycApproved'
        ));
    }

    /**
     * Store a new withdrawal request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // KYC check
        $kycApproved = $user->kycs()->where('status', 'approved')->exists();
        if (!$kycApproved) {
            return back()->with('error', 'You must have an approved KYC to make a withdrawal request.');
        }

        // Check for existing pending withdrawal (any wallet)
        $pendingExists = Withdrawal::where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->exists();

        if ($pendingExists) {
            return back()->with('error', 'You already have a pending withdrawal request. Please wait for it to be processed.');
        }

        $request->validate([
            'wallet_type'    => 'required|in:commission,rank',
            'amount'         => 'required|numeric|min:2000',
            'bank_name'      => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        $walletType = $request->wallet_type;
        $amount = $request->amount;
        $fee = round($amount * 0.02, 2); // 2% admin fee
        $netAmount = $amount - $fee;

        // Check the selected wallet balance
        $balance = ($walletType === 'commission') 
                    ? $user->commission_wallet_balance 
                    : $user->rank_wallet_balance;

        if ($balance < $amount) {
            return back()->with('error', 'Insufficient balance in selected wallet.');
        }

        // Bank details as JSON
        $bankDetails = [
            'bank_name'      => $request->bank_name,
            'account_name'   => $request->account_name,
            'account_number' => $request->account_number,
        ];

        // Generate unique reference
        $reference = 'WDR-' . strtoupper(Str::random(20));

        DB::beginTransaction();
        try {
            // Create withdrawal request (status = pending)
            Withdrawal::create([
                'user_id'      => $user->id,
                'wallet_type'  => $walletType,
                'amount'       => $amount,
                'fee'          => $fee,
                'net_amount'   => $netAmount,
                'bank_details' => $bankDetails,
                'reference'    => $reference,
                'status'       => 'pending',
            ]);

            // Do NOT deduct balance yet – deduction happens on admin approval.
            DB::commit();

            return redirect()->route('member.withdraw.index')
                ->with('success', 'Withdrawal request submitted successfully! You will receive ₦' . number_format($netAmount, 2) . ' after admin approval (₦' . number_format($fee, 2) . ' fee).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit withdrawal request. Please try again.');
        }
    }

    /**
     * Show withdrawal history.
     */
    public function history()
    {
        $withdrawals = Withdrawal::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('member.withdrawals.history', compact('withdrawals'));
    }

    /**
     * Cancel a pending withdrawal (user-initiated).
     */
    public function cancel($id)
    {
        $withdrawal = Withdrawal::where('user_id', Auth::id())
                        ->where('id', $id)
                        ->where('status', 'pending')
                        ->firstOrFail();

        $withdrawal->update([
            'status' => 'cancelled',
            'admin_notes' => 'Cancelled by user',
        ]);

        return redirect()->route('member.withdraw.history')
            ->with('success', 'Withdrawal request cancelled.');
    }
}