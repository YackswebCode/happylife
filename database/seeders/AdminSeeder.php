<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@happylife.com',
            '12345678' => Hash::make('password'),
        ]);
    }
}