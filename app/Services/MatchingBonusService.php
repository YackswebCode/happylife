<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchingBonusService
{
    protected $bonusRate = 0.10; // 10%

    public function processUser(User $user)
    {
        $left = $user->left_pv ?? 0;
        $right = $user->right_pv ?? 0;
        $weaker = min($left, $right);
        $paid = $user->paid_matching_pv ?? 0;

        if ($weaker <= $paid) {
            return false;
        }

        $newPairs = $weaker - $paid;
        $bonus = $newPairs * $this->bonusRate;

        // Get or create commission wallet
        $commissionWallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'type' => 'commission'],
            ['balance' => 0, 'locked_balance' => 0]
        );

        DB::transaction(function () use ($user, $weaker, $bonus, $newPairs, $commissionWallet) {
            // Update user totals
            $user->paid_matching_pv = $weaker;
            $user->matching_pv_bonus_total += $bonus;
            $user->commission_wallet_balance += $bonus;
            $user->save();

            // Update wallet balance
            $commissionWallet->balance += $bonus;
            $commissionWallet->save();

            // Create transaction record
            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'credit',
                'amount'      => $bonus,
                'description' => "Matching bonus for {$newPairs} PV pairs",
                'reference'   => 'MATCH-' . now()->timestamp . '-' . $user->id,
                'status'      => 'completed',
            ]);
        });

        Log::info("Matching bonus processed for user {$user->id}: ₦{$bonus}");

        return true;
    }

    public function processAll()
    {
        $users = User::where('status', 'active')
            ->where(function ($q) {
                $q->where('left_pv', '>', 0)->orWhere('right_pv', '>', 0);
            })
            ->cursor();

        $count = 0;
        foreach ($users as $user) {
            if ($this->processUser($user)) {
                $count++;
            }
        }

        Log::info("Matching bonus processed for {$count} users.");
        return $count;
    }
}