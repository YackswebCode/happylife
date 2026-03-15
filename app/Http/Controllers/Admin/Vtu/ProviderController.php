<?php

namespace App\Http\Controllers\Admin\Vtu;

use App\Http\Controllers\Controller;
use App\Models\VtuProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
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
            'name'     => 'required|string|max:255',
            'category' => 'required|in:airtime,data,cable,electricity',
            'code'     => 'required|string|max:50|unique:vtu_providers,code',
            'logo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'=> 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('vtu-providers', 'public');
            $validated['logo'] = $path;
        }

        VtuProvider::create($validated);

        return redirect()->route('admin.vtu.providers.index')
            ->with('success', 'Provider created successfully.');
    }

    public function show(VtuProvider $provider)
    {
        return view('admin.vtu.providers.show', compact('provider'));
    }

    public function edit(VtuProvider $provider)
    {
        return view('admin.vtu.providers.edit', compact('provider'));
    }

    public function update(Request $request, VtuProvider $provider)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|in:airtime,data,cable,electricity',
            'code'     => 'required|string|max:50|unique:vtu_providers,code,' . $provider->id,
            'logo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'=> 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($provider->logo) {
                Storage::disk('public')->delete($provider->logo);
            }
            $path = $request->file('logo')->store('vtu-providers', 'public');
            $validated['logo'] = $path;
        }

        $provider->update($validated);

        return redirect()->route('admin.vtu.providers.index')
            ->with('success', 'Provider updated successfully.');
    }

    public function destroy(VtuProvider $provider)
    {
        // Check if provider has plans
        if ($provider->plans()->exists()) {
            return back()->with('error', 'Cannot delete provider with associated plans.');
        }
        if ($provider->logo) {
            Storage::disk('public')->delete($provider->logo);
        }
        $provider->delete();

        return redirect()->route('admin.vtu.providers.index')
            ->with('success', 'Provider deleted successfully.');
    }
}