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

        // Hardcoded highest package ID (Lifestyle)
        $highestPackageId = 5;

        // Get all active ranks, ordered by level
        $ranks = Rank::where('is_active', true)
            ->orderBy('level')
            ->get();

        // Current rank of the user
        $currentRank = $user->rank;

        // Weaker side PV
        $leftPv = $user->left_pv ?? 0;
        $rightPv = $user->right_pv ?? 0;
        $weakerSidePv = min($leftPv, $rightPv);

        // Determine next rank
        if ($currentRank) {
            $nextRank = Rank::where('level', '>', $currentRank->level)
                ->where('is_active', true)
                ->orderBy('level')
                ->first();
        } else {
            $nextRank = Rank::where('level', 1)
                ->where('is_active', true)
                ->first();
        }

        // Progress percentage towards next rank
        $progress = 0;
        if ($nextRank) {
            $requiredPv = $nextRank->required_pv;
            if ($requiredPv > 0) {
                $progress = round(min(($weakerSidePv / $requiredPv) * 100, 100), 2);
            }
        }

        // Count user's direct referrals
        $directReferrals = $user->direct_sponsors_count ?? 0;

        // Check if user is eligible to claim next rank
        $canClaim = false;
        if ($nextRank) {
            $canClaim = (
                $leftPv >= $nextRank->required_pv &&          // Left PV requirement
                $rightPv >= $nextRank->required_pv &&         // Right PV requirement
                $user->package_id == $highestPackageId &&     // Highest package requirement
                $directReferrals >= 2 &&                      // Minimum 2 direct referrals
                (!$currentRank || $currentRank->level < $nextRank->level) // Rank progression
            );
        }

        return view('member.ranks.index', compact(
            'user',
            'ranks',
            'currentRank',
            'nextRank',
            'progress',
            'canClaim',
            'weakerSidePv',
            'highestPackageId',
            'directReferrals'
        ));
    }

    /**
     * Claim the achieved rank and receive rewards.
     */
    public function claim(Request $request)
    {
        $user = Auth::user();

        // Hardcoded highest package ID
        $highestPackageId = 5;

        $currentRank = $user->rank;

        // Determine next rank
        if ($currentRank) {
            $nextRank = Rank::where('level', '>', $currentRank->level)
                ->where('is_active', true)
                ->orderBy('level')
                ->first();
        } else {
            $nextRank = Rank::where('level', 1)
                ->where('is_active', true)
                ->first();
        }

        if (!$nextRank) {
            return redirect()->route('member.ranks.index')
                ->with('warning', 'You have already achieved the highest rank.');
        }

        $leftPv = $user->left_pv ?? 0;
        $rightPv = $user->right_pv ?? 0;
        $directReferrals = $user->direct_sponsors_count ?? 0;

        // Validate all 4 conditions
        if ($leftPv < $nextRank->required_pv) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'Your LEFT leg has not met the required PV.');
        }

        if ($rightPv < $nextRank->required_pv) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'Your RIGHT leg has not met the required PV.');
        }

        if ($user->package_id != $highestPackageId) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'You must be on the highest package (Lifestyle) to claim this rank.');
        }

        if ($directReferrals < 2) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'You must have at least 2 direct referrals to claim this rank.');
        }

        if ($currentRank && $currentRank->level >= $nextRank->level) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'You already have this rank or higher.');
        }

        $reward = $nextRank->cash_reward ?? 0;

        DB::transaction(function () use ($user, $nextRank, $reward) {
            // Update user's rank and bonus balances
            $user->rank_id = $nextRank->id;
            $user->rank_bonus_total += $reward;
            $user->rank_wallet_balance += $reward;
            $user->save();

            // Get or create rank wallet
            $rankWallet = Wallet::firstOrCreate(
                ['user_id' => $user->id, 'type' => 'rank'],
                ['balance' => 0, 'locked_balance' => 0]
            );

            // Credit wallet
            $rankWallet->balance += $reward;
            $rankWallet->save();

            // Create transaction record
            WalletTransaction::create([
                'wallet_id'   => $rankWallet->id,
                'user_id'     => $user->id,
                'type'        => WalletTransaction::TYPE_CREDIT,
                'amount'      => $reward,
                'description' => 'Rank Achievement Bonus - ' . $nextRank->name,
                'reference'   => 'RANK-' . $user->id . '-' . time(),
                'status'      => WalletTransaction::STATUS_COMPLETED,
                'metadata'    => [
                    'rank_id'   => $nextRank->id,
                    'rank_name' => $nextRank->name,
                ],
            ]);
        });

        return redirect()->route('member.ranks.index')
            ->with('success', 'Congratulations! You achieved the ' . $nextRank->name . ' rank and earned ₦' . number_format($reward, 2) . ' bonus.');
    }
}