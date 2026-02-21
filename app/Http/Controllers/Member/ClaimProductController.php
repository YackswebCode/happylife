<?php

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
     * Show claim product page – active claim OR claim form.
     */
    public function index()
    {
        $user = Auth::user();

        // No product assigned to user
        if (!$user->product_id) {
            return view('member.claim-product.no-product', [
                'message' => 'You do not have any product to claim. Please contact support.'
            ]);
        }

        // Check for ACTIVE claim (pending, approved, collected)
        $activeClaim = ProductClaim::where('user_id', $user->id)
            ->where('product_id', $user->product_id)
            ->whereIn('status', ['pending', 'approved', 'collected'])
            ->first();

        // If active claim exists → show its status
        if ($activeClaim) {
            return view('member.claim-product.index', [
                'claim'   => $activeClaim,
                'product' => $user->product,
            ]);
        }

        // ===== NO ACTIVE CLAIM =====
        // Check if there is a REJECTED/CANCELLED claim (for notification only)
        $lastRejectedClaim = ProductClaim::where('user_id', $user->id)
            ->where('product_id', $user->product_id)
            ->whereIn('status', ['rejected', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->first();

        // Load all active pickup centers
        $pickupCenters = PickupCenter::where('is_active', true)
            ->with('state')
            ->orderBy('state_id')
            ->get();

        return view('member.claim-product.index', [
            'product'            => $user->product,
            'pickupCenters'      => $pickupCenters,
            'claim'             => null,
            'lastRejectedClaim' => $lastRejectedClaim, // for rejection notice
        ]);
    }

    /**
     * Submit a new product claim.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'pickup_center_id' => 'required|exists:pickup_centers,id',
        ]);

        if (!$user->product_id) {
            return back()->with('error', 'No product associated with your account.');
        }

        // Check for ACTIVE claim – prevent new claim if one is already pending/approved/collected
        $activeClaim = ProductClaim::where('user_id', $user->id)
            ->where('product_id', $user->product_id)
            ->whereIn('status', ['pending', 'approved', 'collected'])
            ->exists();

        if ($activeClaim) {
            return back()->with('error', 'You already have an active claim for this product.');
        }

        // Generate unique claim number
        $claimNumber = 'CLM-' . date('Ymd') . '-' . str_pad(
            ProductClaim::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        // Get pickup center with state
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
                        'id'              => $pickupCenter->id,
                        'name'            => $pickupCenter->name,
                        'address'         => $pickupCenter->address,
                        'contact_person'  => $pickupCenter->contact_person,
                        'contact_phone'   => $pickupCenter->contact_phone,
                        'operating_hours' => $pickupCenter->operating_hours,
                        'state'           => $pickupCenter->state->name ?? '',
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
            return back()->with('error', 'Failed to submit claim. Please try again. ' . $e->getMessage());
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
            'status'      => 'cancelled',
            'admin_notes' => 'Cancelled by user',
        ]);

        return redirect()->route('member.claim-product.index')
            ->with('success', 'Claim cancelled. You can submit a new claim anytime.');
    }
}