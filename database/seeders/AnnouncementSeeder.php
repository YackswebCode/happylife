<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        Announcement::insert([
            [
                'title' => 'Welcome to Happylife Multipurpose Int\'l',
                'content' => 'We are excited to welcome all new members to our platform. Start building your network and enjoy amazing bonuses!',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'New Package Upgrade Bonus',
                'content' => 'Members who upgrade their package this month will receive an additional 5% bonus on their upgrade PV. Don’t miss out!',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'System Maintenance Notice',
                'content' => 'Our platform will undergo scheduled maintenance this Sunday from 12AM to 3AM. Some services may be temporarily unavailable.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}