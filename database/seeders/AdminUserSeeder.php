<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@happylife.com',
            'username' => 'admin001',
            'phone' => '08012345678',
            'password' => Hash::make('Admin@123'),
            'sponsor_id' => 'system',
            'placement_id' => 'system',
            'placement_position' => 'left',
            'package_id' => 4, // Business Guru
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'pickup_center_id' => 1,
            'rank_id' => 8, // PACESETTERS DIRECTOR
            'left_count' => 0,
            'right_count' => 0,
            'total_pv' => 1000000,
            'current_pv' => 1000000,
            'status' => 'active',
            'registration_date' => now(),
            'role' => 'admin'
        ]);

        // Create system user for company
        User::create([
            'name' => 'Happylife Company',
            'email' => 'company@happylife.com',
            'username' => 'happylife',
            'phone' => '08000000000',
            'password' => Hash::make('Company@123'),
            'sponsor_id' => 'system',
            'placement_id' => 'system',
            'placement_position' => 'left',
            'package_id' => 4,
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'pickup_center_id' => 1,
            'rank_id' => 8,
            'left_count' => 0,
            'right_count' => 0,
            'total_pv' => 0,
            'current_pv' => 0,
            'status' => 'active',
            'registration_date' => now(),
            'role' => 'company'
        ]);

        // Create a sample member for testing
        User::create([
            'name' => 'John Doe',
            'email' => 'member@happylife.com',
            'username' => 'johndoe001',
            'phone' => '08011111111',
            'password' => Hash::make('Member@123'),
            'sponsor_id' => 'happylife',
            'placement_id' => 'happylife',
            'placement_position' => 'left',
            'package_id' => 2, // Ohekem
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'pickup_center_id' => 1,
            'rank_id' => 1, // WINNER LEADER
            'left_count' => 2,
            'right_count' => 1,
            'total_pv' => 1500,
            'current_pv' => 500,
            'status' => 'active',
            'registration_date' => now()->subDays(30),
            'role' => 'member'
        ]);
    }
}