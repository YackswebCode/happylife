<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MlmDistributionService
{
    /**
     * Run the full distribution for a newly paid payment.
     * Safe to call multiple times – relies on payment.processed flag.
     */
    public function distribute(Payment $payment): void
    {
        if ($payment->processed) {
            return;
        }

        $user = $payment->user;
        $package = $payment->package;

        if (!$user || !$package) {
            Log::warning('MLM distribution skipped: missing user or package', [
                'payment_id' => $payment->id,
            ]);
            return;
        }

        DB::transaction(function () use ($user, $package, $payment) {
            $this->updatePlacementUserCounts($user);
            $this->updateBinaryTreeCounts($user);
            $this->updateAllPvCounts($user, $package);
            $this->processDirectBonus($user, $package);
            $this->processIndirectBonuses($user, $package);
            $this->processPairBonuses($user);

            $payment->processed = true;
            $payment->save();
        });

        Log::info('MLM distribution completed', [
            'payment_id' => $payment->id,
            'user_id' => $user->id,
            'package_id' => $package->id,
        ]);
    }

    // ------------------------------------------------------
    // ALL THE MLM LOGIC (from your original Controllers)
    // ------------------------------------------------------

    private function updatePlacementUserCounts($user)
    {
        if (!$user->placement_id) return;

        $parent = User::find($user->placement_id);
        if (!$parent) return;

        if ($user->placement_position === 'left') {
            $parent->left_count++;
        } else {
            $parent->right_count++;
        }

        $parent->save();
    }

    private function updateBinaryTreeCounts($user)
    {
        $current = $user;

        while ($current->placement_id) {
            $parent = User::find($current->placement_id);
            if (!$parent) break;

            $leg = $this->getLegForAncestor($parent, $user);

            if ($leg === 'left') {
                $parent->left_count++;
            } else {
                $parent->right_count++;
            }

            $parent->save();
            $current = $parent;
        }
    }

    private function getLegForAncestor($ancestor, $descendant)
    {
        $path = [];
        $current = $descendant;

        while ($current->placement_id && $current->id !== $ancestor->id) {
            $parent = User::find($current->placement_id);
            if (!$parent) break;

            $path[] = $current->placement_position;
            $current = $parent;
        }

        return array_pop($path);
    }

    private function updateAllPvCounts($user, $package)
    {
        $pv = (float) $package->pv;

        $this->updateBinaryTreePv($user, $pv);
        $this->updateUnilevelTreePv($user, $pv);
    }

    private function updateBinaryTreePv($user, $pv)
    {
        $current = $user;

        while ($current->placement_id) {
            $parent = User::find($current->placement_id);
            if (!$parent) break;

            if ($current->placement_position === 'left') {
                $parent->left_pv += $pv;
                $parent->left_available_pv += $pv;
            } else {
                $parent->right_pv += $pv;
                $parent->right_available_pv += $pv;
            }

            $parent->save();
            $current = $parent;
        }
    }

    private function updateUnilevelTreePv($user, $pv)
    {
        $current = $user;

        while ($current->sponsor_id) {
            $sponsor = User::find($current->sponsor_id);
            if (!$sponsor) break;

            $sponsor->total_pv += $pv;
            $sponsor->current_pv += $pv;
            $sponsor->save();

            $current = $sponsor;
        }
    }

    private function processDirectBonus($user, $package)
    {
        if (!$user->sponsor_id) return;

        $sponsor = User::find($user->sponsor_id);
        if (!$sponsor) return;

        $bonus = $package->direct_bonus_amount;
        if ($bonus <= 0) return;

        $sponsor->commission_wallet_balance += $bonus;
        $sponsor->direct_bonus_total += $bonus;
        $sponsor->direct_sponsors_count += 1;
        $sponsor->save();

        Commission::create([
            'user_id' => $sponsor->id,
            'type' => 'direct',
            'amount' => $bonus,
            'from_user_id' => $user->id,
            'from_package_id' => $package->id,
            'status' => 'paid',
        ]);
    }

    private function processIndirectBonuses($user, $package)
    {
        $level = 1;
        $currentSponsorId = $user->sponsor_id;

        while ($currentSponsorId && $level <= 3) {
            $sponsor = User::find($currentSponsorId);
            if (!$sponsor) break;

            if ($level == 2 || $level == 3) {
                $bonus = $package->indirect_bonus_amount;

                $sponsor->commission_wallet_balance += $bonus;

                if ($level == 2) {
                    $sponsor->indirect_level_2_bonus_total += $bonus;
                } elseif ($level == 3) {
                    $sponsor->indirect_level_3_bonus_total += $bonus;
                }

                $sponsor->save();

                Commission::create([
                    'user_id' => $sponsor->id,
                    'type' => 'indirect_level_' . $level,
                    'amount' => $bonus,
                    'from_user_id' => $user->id,
                    'from_package_id' => $package->id,
                    'status' => 'paid',
                ]);
            }

            $currentSponsorId = $sponsor->sponsor_id;
            $level++;
        }
    }

    private function processPairBonuses($user)
    {
        if (!$user->placement_id) return;

        $current = User::find($user->placement_id);

        while ($current) {
            $pairs = min(
                floor($current->left_available_pv / 40),
                floor($current->right_available_pv / 40)
            );

            if ($pairs > 0) {
                $bonus = $pairs * 1500;
                $usedPv = $pairs * 40;

                $current->left_available_pv -= $usedPv;
                $current->right_available_pv -= $usedPv;

                $current->commission_wallet_balance += $bonus;
                $current->save();

                Commission::create([
                    'user_id' => $current->id,
                    'type' => 'pairing',
                    'amount' => $bonus,
                    'from_user_id' => $user->id,
                    'status' => 'paid',
                ]);
            }

            if (!$current->placement_id) break;
            $current = User::find($current->placement_id);
        }
    }
}