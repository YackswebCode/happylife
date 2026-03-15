<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
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

    public function create()
    {
        return view('admin.product_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:product_categories,slug',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product-categories', 'public');
            $validated['image'] = $path;
        }

        ProductCategory::create($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category created successfully.');
    }

    public function show(ProductCategory $productCategory)
    {
        return view('admin.product_categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product_categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:product_categories,slug,' . $productCategory->id,
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($productCategory->image) {
                Storage::disk('public')->delete($productCategory->image);
            }
            $path = $request->file('image')->store('product-categories', 'public');
            $validated['image'] = $path;
        }

        $productCategory->update($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        // Check if category has products
        if ($productCategory->repurchaseProducts()->exists()) {
            return back()->with('error', 'Cannot delete category with associated products.');
        }

        if ($productCategory->image) {
            Storage::disk('public')->delete($productCategory->image);
        }
        $productCategory->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category deleted successfully.');
    }
}