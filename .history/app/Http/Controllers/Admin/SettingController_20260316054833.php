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
        // Get all settings as key-value pairs (with JSON already decoded by model)
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validate site name, logo, favicon (unchanged)
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

        // Handle landing page text fields
        $textFields = [
            'hero_badge', 'hero_title', 'hero_subtitle',
            'stat1_label', 'stat1_value', 'stat2_label', 'stat2_value', 'stat3_label', 'stat3_value',
            'cta_title', 'cta_subtitle', 'cta_button_text', 'cta_button_link', 'cta_secondary_text', 'cta_secondary_link',
            'about_hero_title', 'about_hero_subtitle',
            'about_mission_badge', 'about_mission_title',
            'about_mission_text', 'about_mission_description',
            'about_approach_subtitle',
        ];

        // After saving JSON fields like features, steps, etc.
        if ($request->has('about_approach_steps')) {
            Setting::updateOrCreate(
                ['key' => 'about_approach_steps'],
                ['value' => $request->about_approach_steps, 'type' => 'json']
            );
        }
        
        foreach ($textFields as $field) {
            if ($request->has($field)) {
                Setting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $request->$field, 'type' => 'text']
                );
            }
        }

        // Handle JSON fields: features, steps, faqs, cta_features
        // They come as arrays from the form inputs (we'll build them in the view)
        if ($request->has('features')) {
            Setting::updateOrCreate(
                ['key' => 'landing_features'],
                ['value' => $request->features, 'type' => 'json']
            );
        }
        if ($request->has('steps')) {
            Setting::updateOrCreate(
                ['key' => 'landing_steps'],
                ['value' => $request->steps, 'type' => 'json']
            );
        }
        if ($request->has('faqs')) {
            Setting::updateOrCreate(
                ['key' => 'landing_faqs'],
                ['value' => $request->faqs, 'type' => 'json']
            );
        }
        if ($request->has('cta_features')) {
            Setting::updateOrCreate(
                ['key' => 'cta_features'],
                ['value' => $request->cta_features, 'type' => 'json']
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}