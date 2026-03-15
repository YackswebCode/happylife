<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon'   => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        // Update site name
        Setting::updateOrCreate(
            ['key' => 'site_name'],
            ['value' => $request->site_name, 'type' => 'text']
        );

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            // Delete old logo if exists
            $oldLogo = Setting::where('key', 'logo')->value('value');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $path, 'type' => 'image']
            );
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            $oldFavicon = Setting::where('key', 'favicon')->value('value');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            Setting::updateOrCreate(
                ['key' => 'favicon'],
                ['value' => $path, 'type' => 'image']
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}