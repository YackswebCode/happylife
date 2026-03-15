<?php

namespace App\Http\Controllers\Admin\Vtu;

use App\Http\Controllers\Controller;
use App\Models\VtuApiSetting;
use Illuminate\Http\Request;

class ApiSettingController extends Controller
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
            'name'     => 'required|string|max:255',
            'base_url' => 'required|url',
            'api_key'  => 'required|string',
            'is_active'=> 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // If setting as active, deactivate others
        if ($validated['is_active']) {
            VtuApiSetting::where('is_active', true)->update(['is_active' => false]);
        }

        VtuApiSetting::create($validated);

        return redirect()->route('admin.vtu.api-settings.index')
            ->with('success', 'API setting created successfully.');
    }

    public function edit(VtuApiSetting $apiSetting)
    {
        return view('admin.vtu.api_settings.edit', compact('apiSetting'));
    }

    public function update(Request $request, VtuApiSetting $apiSetting)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'base_url' => 'required|url',
            'api_key'  => 'required|string',
            'is_active'=> 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // If setting as active, deactivate others
        if ($validated['is_active'] && !$apiSetting->is_active) {
            VtuApiSetting::where('is_active', true)->update(['is_active' => false]);
        }

        $apiSetting->update($validated);

        return redirect()->route('admin.vtu.api-settings.index')
            ->with('success', 'API setting updated successfully.');
    }

    public function destroy(VtuApiSetting $apiSetting)
    {
        $apiSetting->delete();

        return redirect()->route('admin.vtu.api-settings.index')
            ->with('success', 'API setting deleted successfully.');
    }
}