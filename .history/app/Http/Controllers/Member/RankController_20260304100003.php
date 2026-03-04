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

        $ranks = Rank::where('is_active', true)
                    ->orderBy('level')
                    ->get();

        $currentRank = $user->rank;

        $nextRank = null;
        $progress = 0;
        $weakerSidePv = min($user->left_pv ?? 0, $user->right_pv ?? 0);

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

        if ($nextRank) {
            $requiredPv = $nextRank->required_pv;
            if ($requiredPv > 0) {
                $progress = round(($weakerSidePv / $requiredPv) * 100, 2);
                $progress = min($progress, 100);
            }
        }

        // ✅ NEW CONDITIONS
        $highestPackage = Package::where('is_active', 1)
                            ->orderByDesc('price')
                            ->first();

        $isOnHighestPackage = $highestPackage 
                                && $user->package_id == $highestPackage->id;

        $hasTwoDirects = $user->direct_sponsors_count >= 2;

        $canClaim = false;

        if ($nextRank) {
            $canClaim =
                ($user->left_pv ?? 0) >= $nextRank->required_pv &&
                ($user->right_pv ?? 0) >= $nextRank->required_pv &&
                (!$currentRank || $currentRank->level < $nextRank->level) &&
                $isOnHighestPackage &&                // ✅ Added
                $hasTwoDirects;                       // ✅ Added
        }

        return view('member.ranks.index', compact(
            'user',
            'ranks',
            'currentRank',
            'nextRank',
            'progress',
            'canClaim',
            'weakerSidePv',
            'isOnHighestPackage',
            'hasTwoDirects'
        ));
    }

    /**
     * Claim the achieved rank and receive rewards.
     */
    public function claim(Request $request)
    {
        $user = Auth::user();

        $currentRank = $user->rank;

        $nextRank = $currentRank
                    ? Rank::where('level', '>', $currentRank->level)
                          ->where('is_active', true)
                          ->orderBy('level')
                          ->first()
                    : Rank::where('level', 1)
                          ->where('is_active', true)
                          ->first();

        if (!$nextRank) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'No rank available to claim.');
        }

        // ✅ CHECK PV REQUIREMENT
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

        // ✅ NEW CONDITION 1: Must be on highest package
        $highestPackage = Package::where('is_active', 1)
                            ->orderByDesc('price')
                            ->first();

        if (!$highestPackage || $user->package_id != $highestPackage->id) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'You must upgrade to the highest package before claiming this rank.');
        }

        // ✅ NEW CONDITION 2: Must have 2 direct referrals
        if ($user->direct_sponsors_count < 2) {
            return redirect()->route('member.ranks.index')
                ->with('error', 'You must directly sponsor at least 2 members before claiming this rank.');
        }

        $reward = $nextRank->cash_reward;

        DB::transaction(function () use ($user, $nextRank, $reward) {

            $user->rank_id = $nextRank->id;
            $user->rank_bonus_total += $reward;
            $user->rank_wallet_balance += $reward;
            $user->save();

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

            $rankWallet->balance += $reward;
            $rankWallet->save();

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
            ->with('success',
                'Congratulations! You have achieved the ' .
                $nextRank->name .
                ' rank and received ₦' .
                number_format($reward, 2) .
                ' bonus!'
            );
    }
}