<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Happylife Multipurpose Int\'l', 'type' => 'text'],
            ['key' => 'logo', 'value' => null, 'type' => 'image'],
            ['key' => 'favicon', 'value' => null, 'type' => 'image'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}