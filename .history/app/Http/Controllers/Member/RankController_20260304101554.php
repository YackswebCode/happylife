<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Package;
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

        // Get all active ranks
        $ranks = Rank::where('is_active', true)
            ->orderBy('level')
            ->get();

        // Current user rank
        $currentRank = $user->rank;

        $leftPv = $user->left_pv ?? 0;
        $rightPv = $user->right_pv ?? 0;

        // Weaker side PV
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

        $progress = 0;

        if ($nextRank) {
            $requiredPv = $nextRank->required_pv;

            if ($requiredPv > 0) {
                $progress = ($weakerSidePv / $requiredPv) * 100;
                if ($progress > 100) $progress = 100;
                $progress = round($progress, 2);
            }
        }

        // Determine the highest package ID
        $highestPackage = Package::where('is_active', 1)
            ->orderBy('price', 'desc')
            ->first();
        $highestPackageId = $highestPackage ? $highestPackage->id : null;

        // Number of direct referrals
        $directReferrals = $user->direct_sponsors_count ?? 0;

        // Check claim eligibility
        $canClaim = false;
        if ($nextRank) {
            if (
                $leftPv >= $nextRank->required_pv &&
                $rightPv >= $nextRank->required_pv &&
                $user->package_id == $highestPackageId &&
                $directReferrals >= 2 &&
                (!$currentRank || $currentRank->level < $nextRank->level)
            ) {
                $canClaim = true;
            }
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
     * Claim rank reward
     */
    public function claim(Request $request)
    {
        $user = Auth::user();

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

        // Highest package check
        $highestPackage = Package::where('is_active', 1)
            ->orderBy('price', 'desc')
            ->first();
        $highestPackageId = $highestPackage ? $highestPackage->id : null;

        // Direct referrals
        $directReferrals = $user->direct_sponsors_count ?? 0;

        // Validate conditions
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
                ->with('error', 'You must be on the highest package to claim this rank.');
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

            // Update User Rank
            $user->rank_id = $nextRank->id;
            $user->rank_bonus_total += $reward;
            $user->rank_wallet_balance += $reward;
            $user->save();

            // Rank Wallet
            $rankWallet = Wallet::firstOrCreate(
                ['user_id' => $user->id, 'type' => 'rank'],
                ['balance' => 0, 'locked_balance' => 0]
            );

            // Credit Wallet
            $rankWallet->balance += $reward;
            $rankWallet->save();

            // Transaction Record
            WalletTransaction::create([
                'wallet_id' => $rankWallet->id,
                'user_id' => $user->id,
                'type' => WalletTransaction::TYPE_CREDIT,
                'amount' => $reward,
                'description' => 'Rank Achievement Bonus - ' . $nextRank->name,
                'reference' => 'RANK-' . $user->id . '-' . time(),
                'status' => WalletTransaction::STATUS_COMPLETED,
                'metadata' => [
                    'rank_id' => $nextRank->id,
                    'rank_name' => $nextRank->name
                ]
            ]);
        });

        return redirect()->route('member.ranks.index')
            ->with('success', 'Congratulations! You achieved the ' . $nextRank->name . ' rank and earned ₦' . number_format($reward, 2) . ' bonus.');
    }
}