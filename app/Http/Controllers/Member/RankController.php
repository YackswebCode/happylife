<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RankController extends Controller
{
    /**
     * Display the rank achievement page.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all active ranks, ordered by level
        $ranks = Rank::where('is_active', true)
                    ->orderBy('level')
                    ->get();

        // Current rank of the user
        $currentRank = $user->rank;

        // Determine next rank (if any)
        $nextRank = null;
        $progress = 0;

        if ($currentRank) {
            $nextRank = $currentRank->nextRank();
        } else {
            // No rank yet – the first rank is level 1
            $nextRank = Rank::where('level', 1)
                            ->where('is_active', true)
                            ->first();
        }

        // Calculate progress towards next rank
        if ($nextRank) {
            $requiredPv = $nextRank->required_pv;
            $progress = ($requiredPv > 0)
                ? round(($user->total_pv / $requiredPv) * 100, 2)
                : 0;
            $progress = min($progress, 100); // cap at 100%
        }

        // Check if user is eligible to claim the next rank
        $canClaim = false;
        if ($nextRank) {
            $canClaim = $user->total_pv >= $nextRank->required_pv
                        && (!$currentRank || $currentRank->level < $nextRank->level);
        }

        return view('member.ranks.index', compact(
            'user', 'ranks', 'currentRank', 'nextRank', 'progress', 'canClaim'
        ));
    }

    /**
     * Claim the achieved rank and receive rewards.
     */
    public function claim(Request $request)
    {
        $user = Auth::user();

        // Determine the next rank to claim
        $currentRank = $user->rank;
        $nextRank = $currentRank
                    ? $currentRank->nextRank()
                    : Rank::where('level', 1)->where('is_active', true)->first();

        if (!$nextRank) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'No rank available to claim.');
        }

        // Verify eligibility
        if ($user->total_pv < $nextRank->required_pv) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'You have not met the PV requirement for this rank.');
        }

        if ($currentRank && $currentRank->level >= $nextRank->level) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'You already have this rank or higher.');
        }

        $reward = $nextRank->cash_reward;

        DB::transaction(function () use ($user, $nextRank, $reward) {
            // 1. Update user's rank and bonus totals
            $user->rank_id = $nextRank->id;
            $user->rank_bonus_total += $reward;          // Lifetime rank bonus tracker
            $user->commission_wallet_balance += $reward; // Withdrawable balance (cached)
            $user->save();

            // 2. Get or create the user's COMMISSION wallet
            //    Use string 'commission' – the constant is NOT defined in your Wallet model
            $commissionWallet = Wallet::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'type'    => 'commission',           // ✅ Fixed: use plain string
                ],
                [
                    'balance'        => 0,
                    'locked_balance' => 0,
                ]
            );

            // 3. Credit the commission wallet
            $commissionWallet->balance += $reward;
            $commissionWallet->save();

            // 4. Create a wallet transaction record
            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => WalletTransaction::TYPE_CREDIT,
                'amount'      => $reward,
                'description' => 'Rank achievement bonus: ' . $nextRank->name,
                'reference'   => 'RANK-' . $nextRank->id . '-' . time(),
                'status'      => WalletTransaction::STATUS_COMPLETED,
                'metadata'    => [
                    'rank_id'   => $nextRank->id,
                    'rank_name' => $nextRank->name,
                ],
            ]);
        });

        return redirect()->route('member.ranks.index')
            ->with('success', 'Congratulations! You have achieved the ' . $nextRank->name . ' rank and received ₦' . number_format($reward, 2) . ' bonus!');
    }
}