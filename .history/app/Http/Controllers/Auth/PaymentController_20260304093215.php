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
use Illuminate\Support\Str;

class PaymentController extends Controller
{

/**
 * Show payment page
 */
public function showPayment()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if (!$user->email_verified_at) {
        return redirect()->route('verification.notice');
    }

    if ($user->status === 'active') {
        return redirect()->route('dashboard');
    }

    $package = Package::find($user->package_id);

    if (!$package) {
        return redirect()->route('register')
            ->withErrors(['error' => 'Package not found. Please register again.']);
    }

    return view('auth.payment', compact('package', 'user'));
}
    /**
     * Activate user after successful online payment
     */
    public function activateUser(Request $request)
    {
        $request->validate([
            'reference' => 'required',
            'gateway' => 'required|in:paystack,flutterwave',
            'package_id' => 'required|exists:packages,id',
            'amount' => 'required|numeric'
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        if ($user->status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'User already activated.'
            ]);
        }

        $package = Package::find($request->package_id);

        if (!$package || $package->price != $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid package or amount mismatch.'
            ]);
        }

        DB::beginTransaction();

        try {

            // ✅ 1. Create Payment Record
            $reference = strtoupper(substr($request->gateway, 0, 2)) . time() . $user->id;

            $payment = Payment::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $package->price,
                'payment_method' => $request->gateway,
                'reference' => $reference,
                'gateway_reference' => $request->reference,
                'status' => 'paid',
                'description' => ucfirst($request->gateway) . ' payment for ' . $package->name . ' package'
            ]);

            // ✅ 2. Activate User
            $user->status = 'active';
            $user->payment_status = 'paid';
            $user->activated_at = now();
            $user->save();

            // ✅ 3. Update Placement Counts
            $this->updatePlacementUserCounts($user);

            // ✅ 4. Update PV (ONLY NOW)
            $this->updateAllPvCounts($user, $package);

            // ✅ 5. Process Bonuses
            $this->processDirectBonus($user, $package);

            // ✅ 6. Process Pair Bonuses
            $this->processPairBonuses($user);

            DB::commit();

            Auth::logout();

            return response()->json([
                'success' => true,
                'message' => 'Account activated successfully.',
                'redirect' => route('login')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Activation failed.',
            ], 500);
        }
    }

    /**
     * Update placement left/right count
     */
    private function updatePlacementUserCounts($newUser)
    {
        if (!$newUser->placement_id) return;

        $placementUser = User::find($newUser->placement_id);
        if (!$placementUser) return;

        if ($newUser->placement_position === 'left') {
            $placementUser->left_count++;
        } else {
            $placementUser->right_count++;
        }

        $placementUser->save();
    }

    /**
     * Update PV logic (corrected)
     */
    // private function updateAllPvCounts($newUser, $package)
    // {
    //     $pv = (float) $package->pv;

    //     // 1️⃣ Give personal PV to new user
    //     $newUser->total_pv += $pv;
    //     $newUser->current_pv += $pv;
    //     $newUser->save();

    //     // 2️⃣ Update Binary Tree
    //     $this->updateBinaryTreePv($newUser, $pv);

    //     // 3️⃣ Update Sponsor Chain (Unilevel)
    //     $this->updateUnilevelTreePv($newUser, $pv);
    // }

    /**
     * Binary Tree PV update
     */
    private function updateBinaryTreePv($newUser, $pv)
    {
        $current = $newUser;

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
     * Unilevel sponsor chain PV
     */
    private function updateUnilevelTreePv($newUser, $pv)
    {
        $current = $newUser;

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
     * Direct + Indirect Bonuses
     */
    private function processDirectBonus($newUser, $package)
    {
        if (!$newUser->sponsor_id) return;

        $sponsor = User::find($newUser->sponsor_id);
        if (!$sponsor) return;

        $bonus = $package->direct_bonus_amount;

        if ($bonus > 0) {
            $sponsor->commission_wallet_balance += $bonus;
            $sponsor->direct_bonus_total += $bonus;
            $sponsor->direct_sponsors_count += 1;
            $sponsor->save();

            Commission::create([
                'user_id' => $sponsor->id,
                'type' => 'direct',
                'amount' => $bonus,
                'description' => 'Direct sponsor bonus for ' . $newUser->name,
                'from_user_id' => $newUser->id,
                'from_package_id' => $package->id,
                'status' => 'paid',
            ]);
        }
    }

    /**
     * Pair Bonus (Corrected: NO total_pv deduction)
     */
    private function processPairBonuses($newUser)
    {
        if (!$newUser->placement_id) return;

        $current = User::find($newUser->placement_id);

        while ($current) {

            $pairs = min(
                floor($current->left_pv / 40),
                floor($current->right_pv / 40)
            );

            if ($pairs > 0) {

                $pairAmount = 1500;
                $totalBonus = $pairs * $pairAmount;

                $usedPv = $pairs * 40;

                // ✅ Deduct ONLY leg PV
                $current->left_pv -= $usedPv;
                $current->right_pv -= $usedPv;

                $current->commission_wallet_balance += $totalBonus;
                $current->save();

                Commission::create([
                    'user_id' => $current->id,
                    'type' => 'pairing',
                    'amount' => $totalBonus,
                    'description' => 'Pair bonus (' . $pairs . ' pairs)',
                    'from_user_id' => $newUser->id,
                    'status' => 'paid',
                ]);
            }

            if (!$current->placement_id) break;

            $current = User::find($current->placement_id);
        }
    }
}