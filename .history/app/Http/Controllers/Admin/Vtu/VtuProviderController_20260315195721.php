<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VtuProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VtuProviderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $providers = VtuProvider::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.vtu.providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.vtu.providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|in:airtime,data,cable,electricity',
            'code'      => 'required|string|max:50|unique:vtu_providers,code',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('vtu/providers', 'public');
            $validated['logo'] = $path;
        }

        VtuProvider::create($validated);

        return redirect()->route('admin.vtu.providers.index')
            ->with('success', 'Provider created.');
    }

    public function show(VtuProvider $vtuProvider)
    {
        return view('admin.vtu.providers.show', compact('vtuProvider'));
    }

    public function edit(VtuProvider $vtuProvider)
    {
        return view('admin.vtu.providers.edit', compact('vtuProvider'));
    }

    public function update(Request $request, VtuProvider $vtuProvider)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|in:airtime,data,cable,electricity',
            'code'      => 'required|string|max:50|unique:vtu_providers,code,' . $vtuProvider->id,
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($vtuProvider->logo) {
                Storage::disk('public')->delete($vtuProvider->logo);
            }
            $path = $request->file('logo')->store('vtu/providers', 'public');
            $validated['logo'] = $path;
        }

        $vtuProvider->update($validated);

        return redirect()->route('admin.vtu.providers.index')
            ->with('success', 'Provider updated.');
    }

    public function destroy(VtuProvider $vtuProvider)
    {
        // Check if has plans
        if ($vtuProvider->plans()->exists()) {
            return back()->with('error', 'Cannot delete provider with existing plans.');
        }
        if ($vtuProvider->logo) {
            Storage::disk('public')->delete($vtuProvider->logo);
        }
        $vtuProvider->delete();
        return redirect()->route('admin.vtu.providers.index')
            ->with('success', 'Provider deleted.');
    }
}