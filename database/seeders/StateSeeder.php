<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\Country;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            'Arewa' => [
                ['name' => 'Adamawa', 'code' => 'AD'],
                ['name' => 'Bauchi', 'code' => 'BA'],
                ['name' => 'Borno', 'code' => 'BO'],
                ['name' => 'Gombe', 'code' => 'GO'],
                ['name' => 'Jigawa', 'code' => 'JI'],
                ['name' => 'Kaduna', 'code' => 'KD'],
                ['name' => 'Kano', 'code' => 'KN'],
                ['name' => 'Katsina', 'code' => 'KT'],
                ['name' => 'Kebbi', 'code' => 'KE'],
                ['name' => 'Kwara', 'code' => 'KW'],
                ['name' => 'Niger', 'code' => 'NI'],
                ['name' => 'Sokoto', 'code' => 'SO'],
                ['name' => 'Taraba', 'code' => 'TA'],
                ['name' => 'Yobe', 'code' => 'YO'],
                ['name' => 'Zamfara', 'code' => 'ZA'],
            ],
            'Biafra' => [
                ['name' => 'Abia', 'code' => 'AB'],
                ['name' => 'Anambra', 'code' => 'AN'],
                ['name' => 'Ebonyi', 'code' => 'EB'],
                ['name' => 'Enugu', 'code' => 'EN'],
                ['name' => 'Imo', 'code' => 'IM'],
            ],
            'Ododuwa' => [
                ['name' => 'Ekiti', 'code' => 'EK'],
                ['name' => 'Lagos', 'code' => 'LG'],
                ['name' => 'Ogun', 'code' => 'OG'],
                ['name' => 'Ondo', 'code' => 'OD'],
                ['name' => 'Osun', 'code' => 'OS'],
                ['name' => 'Oyo', 'code' => 'OY'],
            ],
        ];

        foreach ($states as $countryName => $statesArray) {
            $country = Country::where('name', $countryName)->first();
            if (!$country) continue;

            foreach ($statesArray as $state) {
                State::updateOrCreate(
                    ['code' => $state['code'], 'country_id' => $country->id],
                    array_merge($state, ['is_active' => 1, 'country_id' => $country->id])
                );
            }
        }
    }
}
