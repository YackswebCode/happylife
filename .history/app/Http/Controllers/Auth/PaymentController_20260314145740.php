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

            // Now bonuses & PV start
            $this->updatePlacementUserCounts($user);
            $this->updateAllPvCounts($user, $package);
            $this->processDirectBonus($user, $package);
            $this->processIndirectBonuses($user, $package);
            $this->processPairBonuses($user);

            DB::commit();

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

    private function updateAllPvCounts($user, $package)
    {
        $pv = (float) $package->pv;

        // ❌ No personal PV for new user

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
            } else {
                $parent->right_pv += $pv;
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

    // ✅ DIRECT BONUS (Level 1)
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

    // ✅ INDIRECT BONUS (Level 2 & 3)
    private function processIndirectBonuses($user, $package)
    {
        $level = 1;
        $currentSponsorId = $user->sponsor_id;

        while ($currentSponsorId && $level <= 3) {

            $sponsor = User::find($currentSponsorId);
            if (!$sponsor) break;

            if ($level == 2) {

                $bonus = $package->direct_bonus_amount * 0.5; // 50% of direct

                $sponsor->commission_wallet_balance += $bonus;
                $sponsor->indirect_level_2_bonus_total += $bonus;
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

                $bonus = $package->direct_bonus_amount * 0.3; // 30% of direct

                $sponsor->commission_wallet_balance += $bonus;
                $sponsor->indirect_level_3_bonus_total += $bonus;
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

    private function processPairBonuses($user)
    {
        if (!$user->placement_id) return;

        $current = User::find($user->placement_id);

        while ($current) {

            $pairs = min(
                floor($current->left_pv / 40),
                floor($current->right_pv / 40)
            );

            if ($pairs > 0) {

                $bonus = $pairs * 1500;
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