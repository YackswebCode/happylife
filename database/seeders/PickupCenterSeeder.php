<?php

namespace Database\Seeders;

use App\Models\PickupCenter;
use App\Models\State;
use Illuminate\Database\Seeder;

class PickupCenterSeeder extends Seeder
{
    public function run()
    {
        $states = State::all();

        $pickupCenters = [
            // Lagos Pickup Centers
            [
                'state_id' => State::where('name', 'Lagos')->first()->id,
                'name' => 'Victoria Island Center',
                'address' => '123 Adeola Odeku Street, Victoria Island, Lagos',
                'contact_phone' => '08030000001',
                'contact_person' => 'John Ade',
                'operating_hours' => 'Mon-Fri: 8am-6pm, Sat: 9am-4pm',
                'is_active' => true
            ],
            [
                'state_id' => State::where('name', 'Lagos')->first()->id,
                'name' => 'Ikeja Center',
                'address' => '456 Awolowo Way, Ikeja, Lagos',
                'contact_phone' => '08030000002',
                'contact_person' => 'Sarah Musa',
                'operating_hours' => 'Mon-Fri: 8am-6pm, Sat: 9am-4pm',
                'is_active' => true
            ],
            
            // Abuja Pickup Centers
            [
                'state_id' => State::where('name', 'Abuja')->first()->id,
                'name' => 'Central Business District',
                'address' => '789 Central Area, Abuja',
                'contact_phone' => '08030000003',
                'contact_person' => 'Michael Okoro',
                'operating_hours' => 'Mon-Fri: 8am-6pm, Sat: 9am-4pm',
                'is_active' => true
            ],
            
            // Kano Pickup Centers
            [
                'state_id' => State::where('name', 'Kano')->first()->id,
                'name' => 'Kano Central',
                'address' => '123 Murtala Mohammed Way, Kano',
                'contact_phone' => '08030000004',
                'contact_person' => 'Aisha Bello',
                'operating_hours' => 'Mon-Fri: 8am-6pm, Sat: 9am-4pm',
                'is_active' => true
            ],
            
            // Port Harcourt Pickup Centers
            [
                'state_id' => State::where('name', 'Rivers')->first()->id,
                'name' => 'Port Harcourt Main',
                'address' => '456 Aba Road, Port Harcourt',
                'contact_phone' => '08030000005',
                'contact_person' => 'Chike Obi',
                'operating_hours' => 'Mon-Fri: 8am-6pm, Sat: 9am-4pm',
                'is_active' => true
            ],
        ];

        foreach ($pickupCenters as $center) {
            PickupCenter::create($center);
        }

        // Create pickup centers for other states
        foreach ($states as $state) {
            if (!in_array($state->name, ['Lagos', 'Abuja', 'Kano', 'Rivers'])) {
                PickupCenter::create([
                    'state_id' => $state->id,
                    'name' => $state->name . ' Main Center',
                    'address' => '123 Main Street, ' . $state->name,
                    'contact_phone' => '0803' . rand(1000000, 9999999),
                    'contact_person' => 'Center Manager',
                    'operating_hours' => 'Mon-Fri: 8am-6pm, Sat: 9am-4pm',
                    'is_active' => true
                ]);
            }
        }
    }
}