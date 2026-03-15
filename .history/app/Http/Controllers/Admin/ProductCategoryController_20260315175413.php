<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = ProductCategory::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.product_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'   => 'sometimes|boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        ProductCategory::create($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        return view('admin.product_categories.show', compact('productCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product_categories.edit', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'   => 'sometimes|boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($productCategory->image) {
                Storage::disk('public')->delete($productCategory->image);
            }
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        $productCategory->update($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        // Check if category has products
        if ($productCategory->repurchaseProducts()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated products.');
        }

        if ($productCategory->image) {
            Storage::disk('public')->delete($productCategory->image);
        }
        $productCategory->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}