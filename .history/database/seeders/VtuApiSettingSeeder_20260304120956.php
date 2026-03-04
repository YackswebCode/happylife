<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VtuApiSetting;

class VtuApiSettingSeeder extends Seeder
{
    public function run()
    {
        VtuApiSetting::create([
            'name'      => 'Payscribe Sandbox',
            'base_url'  => 'https://sandbox.payscribe.ng/api/v1',
            'api_key'   => 'ps_pk_test_jGcFtWwBh4OUypZga0BWdJ2Ob5RIqM',
            'is_active' => true,
        ]);
    }
}