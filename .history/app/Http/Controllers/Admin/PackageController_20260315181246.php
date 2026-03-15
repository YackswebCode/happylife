<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $packages = Package::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'pv' => 'required|integer|min:0',
            'product_entitlement' => 'required|string|max:255',
            'direct_bonus_amount' => 'required|numeric|min:0',
            'indirect_bonus_amount' => 'nullable|numeric|min:0',
            'upgrade_bonus_amount' => 'nullable|numeric|min:0',
            'pairing_cap' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['indirect_bonus_amount'] = $validated['indirect_bonus_amount'] ?? 0;
        $validated['upgrade_bonus_amount'] = $validated['upgrade_bonus_amount'] ?? 0;

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function show(Package $package)
    {
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'pv' => 'required|integer|min:0',
            'product_entitlement' => 'required|string|max:255',
            'direct_bonus_amount' => 'required|numeric|min:0',
            'indirect_bonus_amount' => 'nullable|numeric|min:0',
            'upgrade_bonus_amount' => 'nullable|numeric|min:0',
            'pairing_cap' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['indirect_bonus_amount'] = $validated['indirect_bonus_amount'] ?? 0;
        $validated['upgrade_bonus_amount'] = $validated['upgrade_bonus_amount'] ?? 0;

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        // Check if package has users
        if ($package->users()->exists()) {
            return back()->with('error', 'Cannot delete package with associated users.');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}