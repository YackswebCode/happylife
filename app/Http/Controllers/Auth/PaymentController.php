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
     * Activate user after successful online payment - SIMPLIFIED VERSION
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
            
            // STEP 2: Activate user - SIMPLIFIED
            $user->status = 'active';
            $user->payment_status = 'paid';
            $user->activated_at = now();
            $user->package_id = $package->id;
            $user->save();
            
            \Log::info('User activated', ['user_id' => $user->id, 'status' => $user->status]);
            
            // STEP 3: Process commissions (try-catch each step separately)
            try {
                // Direct sponsor bonus
                if ($user->sponsor_id && $package->direct_bonus_amount > 0) {
                    $sponsor = User::find($user->sponsor_id);
                    if ($sponsor) {
                        // Initialize values if they don't exist
                        if (!isset($sponsor->commission_wallet_balance)) {
                            $sponsor->commission_wallet_balance = 0;
                        }
                        if (!isset($sponsor->direct_sponsors_count)) {
                            $sponsor->direct_sponsors_count = 0;
                        }
                        
                        $oldBalance = $sponsor->commission_wallet_balance;
                        $sponsor->commission_wallet_balance += $package->direct_bonus_amount;
                        $sponsor->direct_sponsors_count += 1;
                        $sponsor->save();
                        
                        // Create commission record
                        Commission::create([
                            'user_id' => $sponsor->id,
                            'downline_id' => $user->id,
                            'payment_id' => $payment->id,
                            'amount' => $package->direct_bonus_amount,
                            'type' => 'direct_sponsor',
                            'description' => 'Direct sponsor bonus for ' . $user->name . ' (' . $package->name . ')',
                            'status' => 'paid'
                        ]);
                        
                        // Create wallet transaction
                        WalletTransaction::create([
                            'user_id' => $sponsor->id,
                            'wallet_type' => 'commission',
                            'amount' => $package->direct_bonus_amount,
                            'type' => 'credit',
                            'description' => 'Direct sponsor bonus from ' . $user->name,
                            'balance_before' => $oldBalance,
                            'balance_after' => $sponsor->commission_wallet_balance,
                        ]);
                        
                        \Log::info('Direct sponsor bonus awarded', [
                            'sponsor_id' => $sponsor->id,
                            'amount' => $package->direct_bonus_amount
                        ]);
                        
                        // Indirect bonus (up to 3 generations)
                        $this->processIndirectBonus($sponsor, $user, $package, $payment);
                    }
                }
                
                // Update PV counts
                $this->updatePvCounts($user, $package);
                
            } catch (\Exception $e) {
                // Don't rollback the whole transaction if commission processing fails
                \Log::error('Commission processing error (non-critical): ' . $e->getMessage());
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
     * Process indirect bonus for up to 3 generations
     */
    private function processIndirectBonus($sponsor, $newUser, $package, $payment)
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
            
            $indirectBonus = $package->direct_bonus_amount * 0.10;
            
            // Initialize values if they don't exist
            if (!isset($indirectUser->commission_wallet_balance)) {
                $indirectUser->commission_wallet_balance = 0;
            }
            
            $oldBalance = $indirectUser->commission_wallet_balance;
            $indirectUser->commission_wallet_balance += $indirectBonus;
            $indirectUser->save();
            
            // Create commission record
            Commission::create([
                'user_id' => $indirectUser->id,
                'downline_id' => $newUser->id,
                'payment_id' => $payment->id,
                'amount' => $indirectBonus,
                'type' => 'indirect_sponsor',
                'generation' => $generation,
                'description' => 'Indirect sponsor bonus (Gen ' . $generation . ') for ' . $newUser->name,
                'status' => 'paid'
            ]);
            
            // Create wallet transaction
            WalletTransaction::create([
                'user_id' => $indirectUser->id,
                'wallet_type' => 'commission',
                'amount' => $indirectBonus,
                'type' => 'credit',
                'description' => 'Indirect bonus from ' . $newUser->name . ' (Gen ' . $generation . ')',
                'balance_before' => $oldBalance,
                'balance_after' => $indirectUser->commission_wallet_balance,
            ]);
            
            $currentUser = $indirectUser;
        }
    }

    /**
     * Update PV counts
     */
    private function updatePvCounts($newUser, $package)
    {
        // Update placement user counts
        if ($newUser->placement_id) {
            $placementUser = User::find($newUser->placement_id);
            if ($placementUser) {
                if ($newUser->placement_position === 'left') {
                    $placementUser->left_count = ($placementUser->left_count ?? 0) + 1;
                } elseif ($newUser->placement_position === 'right') {
                    $placementUser->right_count = ($placementUser->right_count ?? 0) + 1;
                }
                
                $placementUser->total_pv = ($placementUser->total_pv ?? 0) + $package->pv;
                $placementUser->current_pv = ($placementUser->current_pv ?? 0) + $package->pv;
                $placementUser->save();
            }
        }
        
        // Update sponsor chain PV
        $this->updateSponsorChainPv($newUser, $package->pv);
    }

    /**
     * Update sponsor chain PV
     */
    private function updateSponsorChainPv($user, $pv)
    {
        $currentUser = $user;
        
        while ($currentUser && $currentUser->sponsor_id) {
            $sponsor = User::find($currentUser->sponsor_id);
            if (!$sponsor) {
                break;
            }
            
            $sponsor->total_pv = ($sponsor->total_pv ?? 0) + $pv;
            $sponsor->current_pv = ($sponsor->current_pv ?? 0) + $pv;
            $sponsor->save();
            
            $currentUser = $sponsor;
        }
    }
}