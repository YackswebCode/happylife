<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Support;

class SupportSeeder extends Seeder
{
    public function run()
    {
        Support::insert([
            [
                'title' => 'Customer Care Support',
                'content' => 'For account issues, wallet problems, withdrawals, or package upgrades, kindly contact our support team during working hours (Mon–Fri, 9AM–5PM).',
                'phone' => '+234 801 234 5678',
                'email' => 'support@happylifeint.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Technical Support',
                'content' => 'Experiencing login errors, payment issues, or technical glitches? Our technical team is available to assist you promptly.',
                'phone' => '+234 809 876 5432',
                'email' => 'tech@happylifeint.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}