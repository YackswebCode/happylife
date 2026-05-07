<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MlmService
{
    /**
     * Distribute PV and bonuses for a completed payment.
     * Safe to call multiple times – it only runs if payment wasn’t already processed.
     */
    public function distributeForPayment(Payment $payment): void
    {
        $newUser = $payment->user;
        $package = $payment->package;

        if (!$newUser || !$package) {
            return;
        }

        // Optional guard: mark payment as processed to avoid double run
        if ($payment->processed) {
            return;
        }

        // ----- 1. Sponsor chain (up to 3 generations) -----
        $sponsor = $this->findUserByReferralCode($newUser->sponsor_id);
        $level = 1;

        // Pre-fetch placement chain for leg determination (newUser + up to 3 ancestors)
        $placementChain = $this->getPlacementChain($newUser);

        while ($sponsor && $level <= 3) {
            // Determine binary leg for this sponsor
            $leg = $this->determineLeg($newUser, $sponsor, $placementChain);

            // ----- PV Allocation -----
            $sponsor->increment('total_pv', $package->pv);
            $sponsor->increment('current_pv', $package->pv);

            if ($leg === 'left') {
                $sponsor->increment('left_pv', $package->pv);
                $sponsor->increment('left_available_pv', $package->pv);
                $sponsor->increment('left_count');
            } elseif ($leg === 'right') {
                $sponsor->increment('right_pv', $package->pv);
                $sponsor->increment('right_available_pv', $package->pv);
                $sponsor->increment('right_count');
            } else {
                Log::warning("MLM: Could not determine binary leg for sponsor {$sponsor->id} and user {$newUser->id}");
            }

            // ----- Bonuses -----
            if ($level === 1) {
                $bonus = $package->direct_bonus_amount;
                $sponsor->increment('direct_bonus_total', $bonus);
                $sponsor->increment('commission_wallet_balance', $bonus);
            } else {
                // Indirect bonus for level 2 and 3
                $bonus = $package->indirect_bonus_amount;
                if ($level === 2) {
                    $sponsor->increment('indirect_level_2_bonus_total', $bonus);
                } else {
                    $sponsor->increment('indirect_level_3_bonus_total', $bonus);
                }
                $sponsor->increment('commission_wallet_balance', $bonus);
            }

            // Next sponsor
            $sponsor = $this->findUserByReferralCode($sponsor->sponsor_id);
            $level++;
        }

        // Mark payment as processed to prevent double distribution
        $payment->processed = true;
        $payment->save();
    }

    /**
     * Find a user by their unique referral code.
     */
    protected function findUserByReferralCode(?string $code): ?User
    {
        if (!$code) {
            return null;
        }
        return User::where('referral_code', $code)->first();
    }

    /**
     * Build the placement chain from the new user upwards (limited to 4 nodes).
     */
    protected function getPlacementChain(User $user): array
    {
        $chain = [];
        $current = $user;
        while ($current && count($chain) < 4) {
            $chain[] = $current;
            $current = $current->placementParent; // relationship defined below
        }
        return $chain;
    }

    /**
     * Determine which leg (left/right) the new user is under the given sponsor.
     */
    protected function determineLeg(User $newUser, User $sponsor, array $placementChain): ?string
    {
        // Find the node in the chain whose direct parent is the sponsor
        foreach ($placementChain as $node) {
            if ($node->placement_id == $sponsor->id) {
                return $node->placement_position; // 'left' or 'right'
            }
        }
        return null;
    }
}