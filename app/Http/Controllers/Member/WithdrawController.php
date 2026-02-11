<?php
// app/Http/Controllers/Member/WithdrawController.php

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

        // Get commission wallet balance from users table
        $commissionBalance = $user->commission_wallet_balance ?? 0;

        // Check if there's already a pending withdrawal
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
            'pendingWithdrawal',
            'recentWithdrawals'
        ));
    }

    /**
     * Store a new withdrawal request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check for existing pending withdrawal
        $pendingExists = Withdrawal::where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->exists();

        if ($pendingExists) {
            return back()->with('error', 'You already have a pending withdrawal request. Please wait for it to be processed.');
        }

        $request->validate([
            'amount'         => 'required|numeric|min:2000',
            'bank_name'      => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            // optional: sort_code, swift, etc.
        ]);

        $amount = $request->amount;
        $fee = round($amount * 0.02, 2); // 2% admin fee
        $netAmount = $amount - $fee;

        // Check commission wallet balance
        if ($user->commission_wallet_balance < $amount) {
            return back()->with('error', 'Insufficient commission wallet balance.');
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
                'amount'       => $amount,
                'fee'          => $fee,
                'net_amount'   => $netAmount,
                'bank_details' => $bankDetails,
                'reference'    => $reference,
                'status'       => 'pending',
            ]);

            // (Optional) You can lock the amount by reducing available balance immediately?
            // According to spec, balance is deducted only after admin approval.
            // So we do NOT deduct now – just create request.

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