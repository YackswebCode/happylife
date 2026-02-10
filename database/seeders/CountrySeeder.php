<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['name' => 'Arewa', 'code' => 'AR', 'is_active' => 1],
            ['name' => 'Biafra', 'code' => 'BI', 'is_active' => 1],
            ['name' => 'Ododuwa', 'code' => 'OD', 'is_active' => 1],
            ['name' => 'Others', 'code' => 'OT', 'is_active' => 1],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['name' => $country['name']],
                $country
            );
        }
    }
}
