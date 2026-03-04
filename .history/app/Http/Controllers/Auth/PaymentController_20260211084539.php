<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\Commission;
use App\Models\WalletTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            return redirect()->route('register')->withErrors(['error' => 'Package not found. Please register again.']);
        }

        return view('auth.payment', compact('package', 'user'));
    }

    /**
     * Process bank transfer
     */
    public function processBankTransfer(Request $request)
    {
        $request->validate([
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'terms' => 'accepted'
        ]);

        $user = Auth::user();
        $package = Package::find($user->package_id);

        if (!$package) {
            return back()->withErrors(['error' => 'Package not found.']);
        }

        DB::beginTransaction();

        try {
            $path = $request->file('proof_of_payment')->store('proofs', 'public');

            $reference = 'BT' . time() . Str::random(6);

            Payment::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $package->price,
                'payment_method' => 'bank_transfer',
                'reference' => $reference,
                'status' => 'pending',
                'proof_url' => $path,
                'description' => 'Bank transfer payment for ' . $package->name . ' package'
            ]);

            // Mark user payment_status as pending
            $user->payment_status = 'pending';
            $user->save();

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Payment proof submitted successfully. Please wait for admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bank transfer error: ' . $e->getMessage());
            \Log::error($e);

            return back()->withErrors(['error' => 'Failed to submit payment proof.']);
        }
    }

    /**
     * Activate user after successful online payment
     */
    public function activateUser(Request $request)
    {
        \Log::info('=== PAYMENT ACTIVATION START ===');
        
        try {
            $request->validate([
                'reference' => 'required',
                'gateway' => 'required|in:paystack,flutterwave',
                'package_id' => 'required|exists:packages,id',
                'amount' => 'required|numeric'
            ]);
            
            \Log::info('Validation passed');
        } catch (\Exception $e) {
            \Log::error('Validation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data: ' . $e->getMessage()
            ], 422);
        }

        $user = Auth::user();
        
        if (!$user) {
            \Log::error('No authenticated user found');
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated. Please login again.'
            ], 401);
        }

        \Log::info('User found', ['user_id' => $user->id, 'email' => $user->email]);

        $package = Package::find($request->package_id);

        if (!$package) {
            \Log::error('Package not found', ['package_id' => $request->package_id]);
            return response()->json([
                'success' => false,
                'message' => 'Package not found.'
            ]);
        }

        if ($package->price != $request->amount) {
            \Log::error('Amount mismatch', [
                'package_price' => $package->price,
                'paid_amount' => $request->amount
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Payment amount does not match package price.'
            ]);
        }

        // Test database connection first
        try {
            DB::connection()->getPdo();
            \Log::info('Database connection OK');
        } catch (\Exception $e) {
            \Log::error('Database connection failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database connection error.'
            ], 500);
        }

        DB::beginTransaction();

        try {
            \Log::info('Starting transaction for user activation');
            
            // STEP 1: Create payment record
            $reference = $request->gateway == 'paystack' ? 'PS' . time() . $user->id : 'FW' . time() . $user->id;
            
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
            
            \Log::info('Payment record created', ['payment_id' => $payment->id]);
            
            // STEP 2: Activate user
            $user->status = 'active';
            $user->payment_status = 'paid';
            $user->activated_at = now();
            $user->package_id = $package->id;
            $user->save();
            
            \Log::info('User activated', ['user_id' => $user->id, 'status' => $user->status]);
            
            // STEP 3: Process commissions and PV
            try {
                // 1. Update placement user counts
                $this->updatePlacementUserCounts($user);
                
                // 2. Update all PV counts (binary tree AND unilevel)
                $this->updateAllPvCounts($user, $package);
                
                // 3. Process direct and indirect bonuses
                $this->processDirectBonus($user, $package, $payment);
                
                // 4. Process pair bonuses from binary tree PV
                $this->processPairBonuses($user, $package, $payment);
                
            } catch (\Exception $e) {
                // Don't rollback the whole transaction if commission processing fails
                \Log::error('Commission processing error (non-critical): ' . $e->getMessage());
                \Log::error('Commission error trace: ' . $e->getTraceAsString());
            }
            
            DB::commit();
            
            \Log::info('Transaction committed successfully');
            
            // Logout user
            Auth::logout();
            
            return response()->json([
                'success' => true,
                'message' => 'Account activated successfully! Please login.',
                'redirect' => route('login')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('ACTIVATION FAILED: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            \Log::error('User: ' . $user->id . ', Package: ' . $package->id . ', Reference: ' . $request->reference);
            
            return response()->json([
                'success' => false,
                'message' => 'Error activating account. ' . (config('app.debug') ? $e->getMessage() : 'Please contact support.'),
                'debug_info' => config('app.debug') ? [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    /**
     * Update placement user's left_count or right_count
     */
    private function updatePlacementUserCounts($newUser)
    {
        if (!$newUser->placement_id) {
            return;
        }

        $placementUser = User::find($newUser->placement_id);
        if (!$placementUser) {
            return;
        }

        if ($newUser->placement_position === 'left') {
            $placementUser->left_count = ($placementUser->left_count ?? 0) + 1;
        } else {
            $placementUser->right_count = ($placementUser->right_count ?? 0) + 1;
        }
        
        $placementUser->save();
        
        \Log::info('Placement user counts updated', [
            'placement_id' => $placementUser->id,
            'left_count' => $placementUser->left_count,
            'right_count' => $placementUser->right_count
        ]);
    }

    /**
     * Update all PV counts (binary tree AND unilevel)
     */
    private function updateAllPvCounts($newUser, $package)
    {
        $pv = (float) $package->pv;
        
        // 1. Update new user's own PV
        $newUser->total_pv = ($newUser->total_pv ?? 0) + $pv;
        $newUser->current_pv = ($newUser->current_pv ?? 0) + $pv;
        $newUser->save();
        
        \Log::info('New user PV updated', [
            'user_id' => $newUser->id,
            'total_pv' => $newUser->total_pv,
            'current_pv' => $newUser->current_pv
        ]);
        
        // 2. Update binary tree PV (left_pv/right_pv)
        $this->updateBinaryTreePv($newUser, $pv);
        
        // 3. Update unilevel tree PV (sponsor chain)
        $this->updateUnilevelTreePv($newUser, $pv);
    }
    
    /**
     * Update binary tree PV (left_pv/right_pv)
     */
    private function updateBinaryTreePv($newUser, $pv)
    {
        if (!$newUser->placement_id) {
            return;
        }
        
        $currentUser = $newUser;
        $currentSide = $newUser->placement_position;
        
        while ($currentUser && $currentUser->placement_id) {
            $placementUser = User::find($currentUser->placement_id);
            if (!$placementUser) {
                break;
            }
            
            // Initialize values
            $placementUser->left_pv = $placementUser->left_pv ?? 0;
            $placementUser->right_pv = $placementUser->right_pv ?? 0;
            
            // Add PV to the correct side
            if ($currentSide === 'left') {
                $placementUser->left_pv += $pv;
            } else {
                $placementUser->right_pv += $pv;
            }
            
            $placementUser->save();
            
            \Log::info('Binary tree PV updated', [
                'user_id' => $placementUser->id,
                'side' => $currentSide,
                'left_pv' => $placementUser->left_pv,
                'right_pv' => $placementUser->right_pv
            ]);
            
            // Move up the tree
            $currentUser = $placementUser;
            $currentSide = $placementUser->placement_position;
        }
    }
    
    /**
     * Update unilevel tree PV (sponsor chain)
     */
    private function updateUnilevelTreePv($newUser, $pv)
    {
        $currentUser = $newUser;
        
        while ($currentUser && $currentUser->sponsor_id) {
            $sponsor = User::find($currentUser->sponsor_id);
            if (!$sponsor) {
                break;
            }
            
            // Initialize values
            $sponsor->total_pv = $sponsor->total_pv ?? 0;
            $sponsor->current_pv = $sponsor->current_pv ?? 0;
            
            // Add PV
            $sponsor->total_pv += $pv;
            $sponsor->current_pv += $pv;
            
            $sponsor->save();
            
            \Log::info('Unilevel tree PV updated', [
                'sponsor_id' => $sponsor->id,
                'total_pv' => $sponsor->total_pv,
                'current_pv' => $sponsor->current_pv
            ]);
            
            $currentUser = $sponsor;
        }
    }
    
    /**
     * Process direct bonus and indirect bonuses
     */
    private function processDirectBonus($newUser, $package, $payment)
    {
        if (!$newUser->sponsor_id || $package->direct_bonus_amount <= 0) {
            return;
        }
        
        $sponsor = User::find($newUser->sponsor_id);
        if (!$sponsor) {
            return;
        }
        
        $bonusAmount = $package->direct_bonus_amount;
        $oldBalance = $sponsor->commission_wallet_balance ?? 0;
        
        // Update sponsor's commission wallet and direct bonus total
        $sponsor->commission_wallet_balance = $oldBalance + $bonusAmount;
        $sponsor->direct_bonus_total = ($sponsor->direct_bonus_total ?? 0) + $bonusAmount;
        $sponsor->direct_sponsors_count = ($sponsor->direct_sponsors_count ?? 0) + 1;
        $sponsor->save();
        
        \Log::info('Direct bonus awarded', [
            'sponsor_id' => $sponsor->id,
            'bonus_amount' => $bonusAmount,
            'old_balance' => $oldBalance,
            'new_balance' => $sponsor->commission_wallet_balance
        ]);
        
        // Create commission record
        Commission::create([
            'user_id' => $sponsor->id,
            'type' => 'direct',
            'amount' => $bonusAmount,
            'description' => 'Direct sponsor bonus for ' . $newUser->name . ' (' . $package->name . ')',
            'from_user_id' => $newUser->id,
            'from_package_id' => $package->id,
            'status' => 'paid',
        
        ]);
        
        
        // Process indirect bonuses (up to 3 generations)
        $this->processIndirectBonuses($sponsor, $newUser, $package, $payment);
    }
    
    /**
     * Process indirect bonuses for up to 3 generations
     */
    private function processIndirectBonuses($sponsor, $newUser, $package, $payment)
    {
        $currentUser = $sponsor;
        
        for ($generation = 1; $generation <= 3; $generation++) {
            if (!$currentUser->sponsor_id) {
                break;
            }
            
            $indirectUser = User::find($currentUser->sponsor_id);
            if (!$indirectUser) {
                break;
            }
            
            // Calculate bonus based on generation
            $bonusPercentage = $this->getIndirectBonusPercentage($generation);
            $indirectBonus = $package->direct_bonus_amount * $bonusPercentage;
            
            if ($indirectBonus <= 0) {
                $currentUser = $indirectUser;
                continue;
            }
            
            $oldBalance = $indirectUser->commission_wallet_balance ?? 0;
            
            // Update commission wallet
            $indirectUser->commission_wallet_balance = $oldBalance + $indirectBonus;
            
            // Update indirect bonus totals based on generation
            if ($generation == 1) {
                $indirectUser->indirect_level_2_bonus_total = ($indirectUser->indirect_level_2_bonus_total ?? 0) + $indirectBonus;
            } elseif ($generation == 2) {
                $indirectUser->indirect_level_3_bonus_total = ($indirectUser->indirect_level_3_bonus_total ?? 0) + $indirectBonus;
            }
            
            $indirectUser->indirect_bonus_total = ($indirectUser->indirect_bonus_total ?? 0) + $indirectBonus;
            $indirectUser->save();
            
            \Log::info('Indirect bonus awarded', [
                'generation' => $generation,
                'user_id' => $indirectUser->id,
                'bonus_amount' => $indirectBonus,
                'old_balance' => $oldBalance,
                'new_balance' => $indirectUser->commission_wallet_balance
            ]);
            
            // Create commission record
            Commission::create([
                'user_id' => $indirectUser->id,
                'type' => 'indirect',
                'amount' => $indirectBonus,
                'description' => 'Indirect sponsor bonus (Gen ' . $generation . ') for ' . $newUser->name,
                'from_user_id' => $newUser->id,
                'from_package_id' => $package->id,
                'status' => 'paid',
              
            ]);
            
         
            
            $currentUser = $indirectUser;
        }
    }
    
    /**
     * Process pair bonuses from binary tree PV
     */
    private function processPairBonuses($newUser, $package, $payment)
    {
        $pv = (float) $package->pv;
        
        // Start from the placement user and go up
        if (!$newUser->placement_id) {
            return;
        }
        
        $currentUser = User::find($newUser->placement_id);
        $currentSide = $newUser->placement_position;
        
        while ($currentUser) {
            // Initialize values
            $currentUser->left_pv = $currentUser->left_pv ?? 0;
            $currentUser->right_pv = $currentUser->right_pv ?? 0;
            $currentUser->commission_wallet_balance = $currentUser->commission_wallet_balance ?? 0;
            
            // Calculate pairs
            $leftPairs = floor($currentUser->left_pv / 40);
            $rightPairs = floor($currentUser->right_pv / 40);
            $pairs = (int) min($leftPairs, $rightPairs);
            
            if ($pairs > 0) {
                $pairAmountPerPair = 1500.00;
                $totalBonus = $pairs * $pairAmountPerPair;
                
                $oldBalance = $currentUser->commission_wallet_balance;
                $currentUser->commission_wallet_balance = $oldBalance + $totalBonus;
                
                // Deduct used PV (40 PV from each leg per pair)
                $usedPv = $pairs * 40;
                $currentUser->left_pv -= $usedPv;
                $currentUser->right_pv -= $usedPv;
                
                // Also reduce total_pv and current_pv
                $currentUser->total_pv = ($currentUser->total_pv ?? 0) - ($usedPv * 2);
                $currentUser->current_pv = ($currentUser->current_pv ?? 0) - ($usedPv * 2);
                
                $currentUser->save();
                
                \Log::info('Pair bonus calculated', [
                    'user_id' => $currentUser->id,
                    'pairs' => $pairs,
                    'bonus_amount' => $totalBonus,
                    'left_pv_after' => $currentUser->left_pv,
                    'right_pv_after' => $currentUser->right_pv
                ]);
                
                // Create commission record for pair bonus
                Commission::create([
                    'user_id' => $currentUser->id,
                    'type' => 'pairing',
                    'amount' => $totalBonus,
                    'description' => 'Pair bonus for ' . $pairs . ' pair(s)',
                    'from_user_id' => $newUser->id,
                    'from_package_id' => $package->id,
                    'status' => 'paid',
                
                ]);
            
            }
            
            // Move up the tree
            if (!$currentUser->placement_id) {
                break;
            }
            
            $nextUser = User::find($currentUser->placement_id);
            if (!$nextUser) {
                break;
            }
            
            $currentUser = $nextUser;
            $currentSide = $currentUser->placement_position;
        }
    }
    
    /**
     * Get indirect bonus percentage based on generation
     */
    private function getIndirectBonusPercentage($generation)
    {
        switch ($generation) {
            case 1: return 0.10; // 10%
            case 2: return 0.05; // 5%
            case 3: return 0.025; // 2.5%
            default: return 0;
        }
    }
}