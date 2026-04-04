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
            'logo'      => 'nullable|image|max:2048',
            'favicon'   => 'nullable|image|max:1024',
        ]);

        // ========================
        // BASIC SETTINGS
        // ========================
        Setting::updateOrCreate(
            ['key' => 'site_name'],
            ['value' => $request->site_name, 'type' => 'text']
        );

        // ========================
        // FILE UPLOADS
        // ========================
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            $old = Setting::where('key', 'logo')->value('value');
            if ($old) Storage::disk('public')->delete($old);

            Setting::updateOrCreate(['key' => 'logo'], ['value' => $path, 'type' => 'image']);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            $old = Setting::where('key', 'favicon')->value('value');
            if ($old) Storage::disk('public')->delete($old);

            Setting::updateOrCreate(['key' => 'favicon'], ['value' => $path, 'type' => 'image']);
        }

        // ========================
        // TEXT FIELDS
        // ========================
        $textFields = [
            'hero_badge','hero_title','hero_subtitle',
            'stat1_label','stat1_value','stat2_label','stat2_value','stat3_label','stat3_value',
            'cta_title','cta_subtitle','cta_button_text','cta_button_link','cta_secondary_text','cta_secondary_link',
            'about_hero_title','about_hero_subtitle',
            'about_mission_badge','about_mission_title',
            'about_mission_text','about_mission_description',
            'about_approach_subtitle',
        ];

        foreach ($textFields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field), 'type' => 'text']
            );
        }

        // ========================
        // CLEAN HELPER
        // ========================
        $cleanArray = function ($array, $requiredKeys = []) {
            return collect($array)
                ->map(function ($item) {
                    return is_array($item)
                        ? array_map(fn($v) => is_string($v) ? trim($v) : $v, $item)
                        : trim($item);
                })
                ->filter(function ($item) use ($requiredKeys) {
                    if (!is_array($item)) return !empty($item);

                    foreach ($requiredKeys as $key) {
                        if (!empty($item[$key])) return true;
                    }
                    return false;
                })
                ->values()
                ->toArray();
        };

        // ========================
        // FEATURES
        // ========================
        Setting::updateOrCreate(
            ['key' => 'landing_features'],
            ['value' => $cleanArray($request->input('features', []), ['title','description']), 'type' => 'json']
        );

        // ========================
        // STEPS
        // ========================
        Setting::updateOrCreate(
            ['key' => 'landing_steps'],
            ['value' => $cleanArray($request->input('steps', []), ['title','description']), 'type' => 'json']
        );

        // ========================
        // FAQs
        // ========================
        Setting::updateOrCreate(
            ['key' => 'landing_faqs'],
            ['value' => $cleanArray($request->input('faqs', []), ['question','answer']), 'type' => 'json']
        );

        // ========================
        // ABOUT APPROACH
        // ========================
        Setting::updateOrCreate(
            ['key' => 'about_approach_steps'],
            ['value' => $cleanArray($request->input('about_approach_steps', []), ['title','desc']), 'type' => 'json']
        );

        // ========================
        // CTA FEATURES
        // ========================
        Setting::updateOrCreate(
            ['key' => 'cta_features'],
            ['value' => $cleanArray($request->input('cta_features', [])), 'type' => 'json']
        );

        // ========================
        // TEAM MEMBERS ðŸ”¥
        // ========================
        $team = collect($request->input('team', []))
            ->map(function ($member, $index) use ($request) {

                // Upload image
                if ($request->hasFile("team.$index.image")) {
                    $path = $request->file("team.$index.image")->store('settings/team', 'public');
                    $member['image'] = $path;
                }

                return [
                    'name'  => trim($member['name'] ?? ''),
                    'role'  => trim($member['role'] ?? ''),
                    'image' => $member['image'] ?? null,
                ];
            })
            ->filter(fn($m) => !empty($m['name']) || !empty($m['role']))
            ->values()
            ->toArray();

        Setting::updateOrCreate(
            ['key' => 'team_members'],
            ['value' => $team, 'type' => 'json']
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}