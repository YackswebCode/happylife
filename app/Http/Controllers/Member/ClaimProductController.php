<?php
// app/Http/Controllers/Member/ClaimProductController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ProductClaim;
use App\Models\PickupCenter;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClaimProductController extends Controller
{
    /**
     * Show claim product page – either pending claim or claim form.
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user has a product to claim (registered with a product)
       if (!$user->product_id) {
            return view('member.claim-product.no-product', [
                'message' => 'You do not have any product to claim. Please contact support.'
            ]);
        }

        // Check if user already has a pending or approved claim for this product
        $existingClaim = ProductClaim::where('user_id', $user->id)
            ->where('product_id', $user->product_id)
            ->first();

        if ($existingClaim) {
            // Show claim status and receipt button
            return view('member.claim-product.index', [
                'claim'   => $existingClaim,
                'product' => $user->product,
            ]);
        }

        // No claim yet – show claim form with pickup centers
        $pickupCenters = PickupCenter::where('is_active', true)
            ->with('state')
            ->orderBy('state_id')
            ->get();

        return view('member.claim-product.index', [
            'product'        => $user->product,
            'pickupCenters'  => $pickupCenters,
            'claim'          => null,
        ]);
    }

    /**
     * Submit a product claim.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate
        $request->validate([
            'pickup_center_id' => 'required|exists:pickup_centers,id',
        ]);

        // Ensure user has a product and no existing claim
        if (!$user->product_id) {
            return back()->with('error', 'No product associated with your account.');
        }

        $existingClaim = ProductClaim::where('user_id', $user->id)
            ->where('product_id', $user->product_id)
            ->exists();

        if ($existingClaim) {
            return back()->with('error', 'You have already claimed this product.');
        }

        // Generate unique claim number
        $claimNumber = 'CLM-' . date('Ymd') . '-' . str_pad(ProductClaim::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        // Fetch pickup center details for snapshot
        $pickupCenter = PickupCenter::with('state')->findOrFail($request->pickup_center_id);

        DB::beginTransaction();
        try {
            $claim = ProductClaim::create([
                'user_id'           => $user->id,
                'product_id'        => $user->product_id,
                'pickup_center_id'  => $pickupCenter->id,
                'claim_number'      => $claimNumber,
                'status'            => 'pending',
                'claimed_at'        => now(),
                'receipt_data'      => [
                    'product' => [
                        'id'    => $user->product->id,
                        'name'  => $user->product->name,
                        'price' => $user->product->price,
                        'pv'    => $user->product->pv,
                    ],
                    'pickup_center' => [
                        'id'        => $pickupCenter->id,
                        'name'      => $pickupCenter->name,
                        'address'   => $pickupCenter->address,
                        'contact_person' => $pickupCenter->contact_person,
                        'contact_phone'  => $pickupCenter->contact_phone,
                        'operating_hours' => $pickupCenter->operating_hours,
                        'state'     => $pickupCenter->state->name ?? '',
                    ],
                    'user' => [
                        'id'       => $user->id,
                        'name'     => $user->name,
                        'email'    => $user->email,
                        'phone'    => $user->phone,
                        'username' => $user->username,
                    ],
                ],
            ]);

            DB::commit();

            return redirect()->route('member.claim-product.receipt', $claim->id)
                ->with('success', 'Product claimed successfully! You can print your receipt below.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit claim. Please try again.');
        }
    }

    /**
     * Show printable receipt.
     */
    public function receipt($id)
    {
        $claim = ProductClaim::with(['product', 'pickupCenter', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('member.claim-product.receipt', compact('claim'));
    }

    /**
     * Cancel a pending claim (user-initiated).
     */
    public function cancel($id)
    {
        $claim = ProductClaim::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $claim->update([
            'status'      => 'rejected', // or 'cancelled' – we can use 'rejected' for admin, 'cancelled' for user
            'admin_notes' => 'Cancelled by user',
        ]);

        return redirect()->route('member.claim-product.index')
            ->with('success', 'Claim cancelled. You can submit a new claim if needed.');
    }
}