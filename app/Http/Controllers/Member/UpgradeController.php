<?php
// app/Http/Controllers/Member/UpgradeController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductClaim;
use App\Models\Upgrade;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpgradeController extends Controller
{
    /**
     * Show upgrade form with available packages.
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure user has a current package
        if (!$user->package_id) {
            return redirect()->route('member.dashboard')
                ->with('error', 'You do not have a package to upgrade from.');
        }

        $currentPackage = $user->package;
        $upgradeablePackages = $user->upgradeable_packages;

        // Get commission wallet balance
        $commissionWallet = Wallet::where('user_id', $user->id)
            ->where('type', 'commission')
            ->first();

        $walletBalance = $commissionWallet ? $commissionWallet->balance : 0;

        return view('member.upgrade.index', compact(
            'currentPackage',
            'upgradeablePackages',
            'walletBalance'
        ));
    }

    /**
     * Process package upgrade.
     */
    public function process(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $newPackage = Package::findOrFail($request->package_id);

        // Validation
        if (!$user->package_id) {
            return back()->with('error', 'You do not have a package to upgrade.');
        }

        if ($user->package_id == $newPackage->id) {
            return back()->with('error', 'You are already on this package.');
        }

        if ($newPackage->order <= $user->package->order) {
            return back()->with('error', 'You can only upgrade to a higher package.');
        }

        // Calculate difference
        $oldPrice = $user->package->price;
        $newPrice = $newPackage->price;
        $difference = $newPrice - $oldPrice;

        if ($difference <= 0) {
            return back()->with('error', 'Invalid upgrade amount.');
        }

        // Check wallet balance (using commission wallet)
        $commissionWallet = Wallet::where('user_id', $user->id)
            ->where('type', 'commission')
            ->first();

        if (!$commissionWallet || $commissionWallet->balance < $difference) {
            return back()->with('error', 'Insufficient commission wallet balance. You need ₦' . number_format($difference, 2));
        }

        // Get product for new package
        $newProduct = Product::where('package_id', $newPackage->id)->first();
        if (!$newProduct) {
            return back()->with('error', 'No product found for the selected package. Please contact support.');
        }

        // Generate reference
        $reference = 'UPG-' . strtoupper(Str::random(20));

        DB::beginTransaction();
        try {
            // 1. Deduct from commission wallet
            $commissionWallet->balance -= $difference;
            $commissionWallet->save();

            // 2. Record wallet transaction
            WalletTransaction::create([
                'wallet_id'   => $commissionWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $difference,
                'description' => 'Package upgrade: ' . $user->package->name . ' → ' . $newPackage->name,
                'reference'   => $reference,
                'status'      => 'completed',
            ]);

            // 3. Update user's package and product
            $user->package_id = $newPackage->id;
            $user->product_id = $newProduct->id;
            $user->save();

            // 4. Record upgrade
            Upgrade::create([
                'user_id'          => $user->id,
                'old_package_id'   => $user->package->id,
                'new_package_id'   => $newPackage->id,
                'difference_amount' => $difference,
                'payment_method'   => 'commission_wallet',
                'status'           => 'completed',
                'reference'        => $reference,
            ]);

            // 5. Create product claim for the new product (pending)
            // Check if a claim already exists for this user and product (should not, but just in case)
            $existingClaim = ProductClaim::where('user_id', $user->id)
                ->where('product_id', $newProduct->id)
                ->first();

            if (!$existingClaim) {
                // Generate claim number
                $claimNumber = 'CLM-' . date('Ymd') . '-' . str_pad(ProductClaim::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

                ProductClaim::create([
                    'user_id'          => $user->id,
                    'product_id'       => $newProduct->id,
                    'pickup_center_id' => $user->pickup_center_id, // use existing pickup center or null
                    'claim_number'     => $claimNumber,
                    'status'           => 'pending',
                    'claimed_at'       => now(),
                    'receipt_data'     => [
                        'product' => [
                            'id'    => $newProduct->id,
                            'name'  => $newProduct->name,
                            'price' => $newProduct->price,
                            'pv'    => $newProduct->pv,
                        ],
                        'user' => [
                            'id'       => $user->id,
                            'name'     => $user->name,
                            'email'    => $user->email,
                            'username' => $user->username,
                        ],
                        'upgrade_reference' => $reference,
                    ],
                ]);
            }

            DB::commit();

            return redirect()->route('member.upgrade.index')
                ->with('success', 'Congratulations! You have successfully upgraded to ' . $newPackage->name . '. Your new product is ready to claim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Upgrade failed. Please try again. ' . $e->getMessage());
        }
    }
}