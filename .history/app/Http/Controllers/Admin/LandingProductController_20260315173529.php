<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = LandingProduct::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.landing_products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.landing_products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string',
            'display_price' => 'required|numeric|min:0',
            'category'      => 'nullable|string|max:255',
            'order'         => 'nullable|integer|min:0',
            'is_active'     => 'sometimes|boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('landing-products', 'public');
            $validated['image'] = $path;
        }

        LandingProduct::create($validated);

        return redirect()->route('admin.landing-products.index')
            ->with('success', 'Landing product created successfully.');
    }

    public function show(LandingProduct $landingProduct)
    {
        return view('admin.landing_products.show', compact('landingProduct'));
    }

    public function edit(LandingProduct $landingProduct)
    {
        return view('admin.landing_products.edit', compact('landingProduct'));
    }

    public function update(Request $request, LandingProduct $landingProduct)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string',
            'display_price' => 'required|numeric|min:0',
            'category'      => 'nullable|string|max:255',
            'order'         => 'nullable|integer|min:0',
            'is_active'     => 'sometimes|boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($landingProduct->image) {
                Storage::disk('public')->delete($landingProduct->image);
            }
            $path = $request->file('image')->store('landing-products', 'public');
            $validated['image'] = $path;
        }

        $landingProduct->update($validated);

        return redirect()->route('admin.landing-products.index')
            ->with('success', 'Landing product updated successfully.');
    }

    public function destroy(LandingProduct $landingProduct)
    {
        if ($landingProduct->image) {
            Storage::disk('public')->delete($landingProduct->image);
        }
        $landingProduct->delete();

        return redirect()->route('admin.landing-products.index')
            ->with('success', 'Landing product deleted successfully.');
    }
}