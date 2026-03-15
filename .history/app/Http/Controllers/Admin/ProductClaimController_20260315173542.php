<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductClaim;
use Illuminate\Http\Request;

class ProductClaimController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $claims = ProductClaim::with(['user', 'product'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('claim_number', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.product_claims.index', compact('claims'));
    }

    public function show(ProductClaim $productClaim)
    {
        $productClaim->load(['user', 'product', 'pickupCenter']);
        return view('admin.product_claims.show', compact('productClaim'));
    }

    public function edit(ProductClaim $productClaim)
    {
        return view('admin.product_claims.edit', compact('productClaim'));
    }

    public function update(Request $request, ProductClaim $productClaim)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,approved,rejected,collected',
            'admin_notes' => 'nullable|string',
        ]);

        // Update timestamps based on status
        if ($validated['status'] == 'approved' && $productClaim->status != 'approved') {
            $validated['approved_at'] = now();
        } elseif ($validated['status'] == 'collected' && $productClaim->status != 'collected') {
            $validated['collected_at'] = now();
        }

        $productClaim->update($validated);

        return redirect()->route('admin.product-claims.index')
            ->with('success', 'Claim updated successfully.');
    }

    // Disable create, store, destroy
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function destroy(ProductClaim $productClaim) { abort(404); }
}