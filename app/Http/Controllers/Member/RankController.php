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

        // Determine next rank (based on both sides PV)
        $nextRank = null;
        $progress = 0;
        $weakerSidePv = min($user->left_pv ?? 0, $user->right_pv ?? 0);

        if ($currentRank) {
            // Next rank is the one with level > current rank
            $nextRank = Rank::where('level', '>', $currentRank->level)
                            ->where('is_active', true)
                            ->orderBy('level')
                            ->first();
        } else {
            // No rank yet – the first rank is level 1
            $nextRank = Rank::where('level', 1)
                            ->where('is_active', true)
                            ->first();
        }

        // Calculate progress towards next rank (based on weaker side)
        if ($nextRank) {
            $requiredPv = $nextRank->required_pv; // per side
            if ($requiredPv > 0) {
                $progress = round(($weakerSidePv / $requiredPv) * 100, 2);
                $progress = min($progress, 100);
            }
        }

        // Check if user is eligible to claim the next rank
        $canClaim = false;
        if ($nextRank) {
            $canClaim = ($user->left_pv ?? 0) >= $nextRank->required_pv
                        && ($user->right_pv ?? 0) >= $nextRank->required_pv
                        && (!$currentRank || $currentRank->level < $nextRank->level);
        }

        return view('member.ranks.index', compact(
            'user', 'ranks', 'currentRank', 'nextRank', 'progress', 'canClaim', 'weakerSidePv'
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
                    ? Rank::where('level', '>', $currentRank->level)
                          ->where('is_active', true)
                          ->orderBy('level')
                          ->first()
                    : Rank::where('level', 1)->where('is_active', true)->first();

        if (!$nextRank) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'No rank available to claim.');
        }

        // Verify eligibility (both sides must meet required PV)
        if (($user->left_pv ?? 0) < $nextRank->required_pv) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'Your left side does not have enough PV for this rank.');
        }

        if (($user->right_pv ?? 0) < $nextRank->required_pv) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'Your right side does not have enough PV for this rank.');
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
            $user->rank_wallet_balance += $reward;       // Withdrawable rank balance
            $user->save();

            // 2. Get or create the user's RANK wallet
            $rankWallet = Wallet::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'type'    => 'rank',
                ],
                [
                    'balance'        => 0,
                    'locked_balance' => 0,
                ]
            );

            // 3. Credit the rank wallet
            $rankWallet->balance += $reward;
            $rankWallet->save();

            // 4. Create a wallet transaction record
            WalletTransaction::create([
                'wallet_id'   => $rankWallet->id,
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