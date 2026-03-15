<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\Commission;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function showPayment()
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');
        if (!$user->email_verified_at) return redirect()->route('verification.notice');
        if ($user->status === 'active') return redirect()->route('dashboard');

        $package = Package::find($user->package_id);

        if (!$package) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Package not found.']);
        }

        return view('auth.payment', compact('package', 'user'));
    }

    public function activateUser(Request $request)
    {
        $request->validate([
            'reference' => 'required',
            'gateway' => 'required|in:paystack,flutterwave',
            'package_id' => 'required|exists:packages,id',
            'amount' => 'required|numeric'
        ]);

        $user = Auth::user();
        if (!$user || $user->status === 'active') {
            return response()->json(['success' => false], 400);
        }

        $package = Package::find($request->package_id);
        if (!$package || $package->price != $request->amount) {
            return response()->json(['success' => false], 400);
        }

        DB::beginTransaction();

        try {
            // Record the payment
            Payment::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $package->price,
                'payment_method' => $request->gateway,
                'reference' => strtoupper(substr($request->gateway, 0, 2)) . time(),
                'gateway_reference' => $request->reference,
                'status' => 'paid',
            ]);

            // Activate user
            $user->status = 'active';
            $user->payment_status = 'paid';
            $user->activated_at = now();
            $user->save();

            // Update placement counts for the immediate parent
            $this->updatePlacementUserCounts($user);

            // Distribute PV up the binary and unilevel trees
            $this->updateAllPvCounts($user, $package);

            // Award bonuses
            $this->processDirectBonus($user, $package);
            $this->processIndirectBonuses($user, $package);
            $this->processPairBonuses($user);

            DB::commit();

            // Log out the user (or redirect to login)
            Auth::logout();

            return response()->json([
                'success' => true,
                'redirect' => route('login')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Increment the direct left/right count of the placement parent.
     */
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

    /**
     * Start PV distribution: binary tree (left/right PV) and unilevel tree (total/current PV).
     */
    private function updateAllPvCounts($user, $package)
    {
        $pv = (float) $package->pv;

        // Binary tree: add PV to ancestors' left_pv / right_pv based on placement path
        $this->updateBinaryTreePv($user, $pv);

        // Unilevel tree: add PV to sponsors' total_pv / current_pv
        $this->updateUnilevelTreePv($user, $pv);
    }

    /**
     * Add PV to the binary ancestors (left_pv / right_pv).
     */
    private function updateBinaryTreePv($user, $pv)
    {
        $current = $user;

        while ($current->placement_id) {
            $parent = User::find($current->placement_id);
            if (!$parent) break;

            if ($current->placement_position === 'left') {
                $parent->left_pv += $pv;
            } else {
                $parent->right_pv += $pv;
            }

            $parent->save();
            $current = $parent;
        }
    }

    /**
     * Add PV to the unilevel ancestors (total_pv / current_pv).
     */
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

    /**
     * Award direct bonus to the immediate sponsor (level 1).
     */
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

    /**
     * Award indirect bonuses to level 2 and level 3 sponsors using the package's indirect_bonus_amount.
     */
    private function processIndirectBonuses($user, $package)
    {
        $level = 1;
        $currentSponsorId = $user->sponsor_id;

        while ($currentSponsorId && $level <= 3) {
            $sponsor = User::find($currentSponsorId);
            if (!$sponsor) break;

            // Level 2 and 3 get the package's indirect_bonus_amount
            if ($level == 2) {
                $bonus = $package->indirect_bonus_amount;

                $sponsor->commission_wallet_balance += $bonus;
                if (isset($sponsor->indirect_level_2_bonus_total)) {
                    $sponsor->indirect_level_2_bonus_total += $bonus;
                }
                $sponsor->save();

                Commission::create([
                    'user_id' => $sponsor->id,
                    'type' => 'indirect_level_2',
                    'amount' => $bonus,
                    'from_user_id' => $user->id,
                    'from_package_id' => $package->id,
                    'status' => 'paid',
                ]);
            }

            if ($level == 3) {
                $bonus = $package->indirect_bonus_amount;

                $sponsor->commission_wallet_balance += $bonus;
                if (isset($sponsor->indirect_level_3_bonus_total)) {
                    $sponsor->indirect_level_3_bonus_total += $bonus;
                }
                $sponsor->save();

                Commission::create([
                    'user_id' => $sponsor->id,
                    'type' => 'indirect_level_3',
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

    /**
     * Process pairing bonuses up the binary tree.
     */
    private function processPairBonuses($user)
    {
        if (!$user->placement_id) return;

        $current = User::find($user->placement_id);

        while ($current) {
            // Calculate how many pairs can be formed (each pair uses 40 PV from each leg)
            $pairs = min(
                floor($current->left_pv / 40),
                floor($current->right_pv / 40)
            );

            if ($pairs > 0) {
                $bonus = $pairs * 1500; // 1500 per pair (adjust as needed)
                $usedPv = $pairs * 40;

                $current->left_pv -= $usedPv;
                $current->right_pv -= $usedPv;

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