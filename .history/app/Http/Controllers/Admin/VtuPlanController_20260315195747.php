<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VtuPlan;
use App\Models\VtuProvider;
use Illuminate\Http\Request;

class VtuPlanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $provider_id = $request->input('provider_id');
        $category = $request->input('category'); // via provider

        $plans = VtuPlan::with('provider')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('api_code', 'like', "%{$search}%");
            })
            ->when($provider_id, function ($query, $provider_id) {
                return $query->where('provider_id', $provider_id);
            })
            ->when($category, function ($query, $category) {
                return $query->whereHas('provider', function ($q) use ($category) {
                    $q->where('category', $category);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $providers = VtuProvider::where('is_active', true)->get();

        return view('admin.vtu.plans.index', compact('plans', 'providers'));
    }

    public function create()
    {
        $providers = VtuProvider::where('is_active', true)->get();
        return view('admin.vtu.plans.create', compact('providers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:vtu_providers,id',
            'api_code'    => 'nullable|string|max:255',
            'name'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'validity'    => 'nullable|string|max:50',
            'size'        => 'nullable|string|max:50',
            'is_active'   => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        VtuPlan::create($validated);

        return redirect()->route('admin.vtu.plans.index')
            ->with('success', 'Plan created.');
    }

    public function show(VtuPlan $vtuPlan)
    {
        $vtuPlan->load('provider');
        return view('admin.vtu.plans.show', compact('vtuPlan'));
    }

    public function edit(VtuPlan $vtuPlan)
    {
        $providers = VtuProvider::where('is_active', true)->get();
        return view('admin.vtu.plans.edit', compact('vtuPlan', 'providers'));
    }

    public function update(Request $request, VtuPlan $vtuPlan)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:vtu_providers,id',
            'api_code'    => 'nullable|string|max:255',
            'name'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'validity'    => 'nullable|string|max:50',
            'size'        => 'nullable|string|max:50',
            'is_active'   => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vtuPlan->update($validated);

        return redirect()->route('admin.vtu.plans.index')
            ->with('success', 'Plan updated.');
    }

    public function destroy(VtuPlan $vtuPlan)
    {
        $vtuPlan->delete();
        return redirect()->route('admin.vtu.plans.index')
            ->with('success', 'Plan deleted.');
    }
}