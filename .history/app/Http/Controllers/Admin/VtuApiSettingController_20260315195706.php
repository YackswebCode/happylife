<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VtuApiSetting;
use Illuminate\Http\Request;

class VtuApiSettingController extends Controller
{
    public function index()
    {
        $settings = VtuApiSetting::latest()->paginate(15);
        return view('admin.vtu.api_settings.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.vtu.api_settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'base_url'  => 'required|url|max:255',
            'api_key'   => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        VtuApiSetting::create($validated);

        return redirect()->route('admin.vtu.api-settings.index')
            ->with('success', 'API setting created.');
    }

    public function show(VtuApiSetting $vtuApiSetting)
    {
        return view('admin.vtu.api_settings.show', compact('vtuApiSetting'));
    }

    public function edit(VtuApiSetting $vtuApiSetting)
    {
        return view('admin.vtu.api_settings.edit', compact('vtuApiSetting'));
    }

    public function update(Request $request, VtuApiSetting $vtuApiSetting)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'base_url'  => 'required|url|max:255',
            'api_key'   => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vtuApiSetting->update($validated);

        return redirect()->route('admin.vtu.api-settings.index')
            ->with('success', 'API setting updated.');
    }

    public function destroy(VtuApiSetting $vtuApiSetting)
    {
        $vtuApiSetting->delete();
        return redirect()->route('admin.vtu.api-settings.index')
            ->with('success', 'API setting deleted.');
    }
}