<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepurchaseProduct;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepurchaseProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $products = RepurchaseProduct::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.repurchase_products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.repurchase_products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'pv_value'    => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'sku'         => 'required|string|unique:repurchase_products,sku',
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('repurchase-products', 'public');
            $validated['image'] = $path;
        }

        RepurchaseProduct::create($validated);

        return redirect()->route('admin.repurchase-products.index')
            ->with('success', 'Repurchase product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RepurchaseProduct $repurchaseProduct)
    {
        $repurchaseProduct->load('category');
        return view('admin.repurchase_products.show', compact('repurchaseProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepurchaseProduct $repurchaseProduct)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.repurchase_products.edit', compact('repurchaseProduct', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepurchaseProduct $repurchaseProduct)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'pv_value'    => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'sku'         => 'required|string|unique:repurchase_products,sku,' . $repurchaseProduct->id,
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($repurchaseProduct->image) {
                Storage::disk('public')->delete($repurchaseProduct->image);
            }
            $path = $request->file('image')->store('repurchase-products', 'public');
            $validated['image'] = $path;
        }

        $repurchaseProduct->update($validated);

        return redirect()->route('admin.repurchase-products.index')
            ->with('success', 'Repurchase product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepurchaseProduct $repurchaseProduct)
    {
        if ($repurchaseProduct->image) {
            Storage::disk('public')->delete($repurchaseProduct->image);
        }
        $repurchaseProduct->delete();

        return redirect()->route('admin.repurchase-products.index')
            ->with('success', 'Repurchase product deleted successfully.');
    }
}