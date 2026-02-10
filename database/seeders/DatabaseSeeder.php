<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            PickupCenterSeeder::class,
            PackageSeeder::class,
            RankSeeder::class,
            VtuProviderSeeder::class,
            VtuPlanSeeder::class,
            LandingProductSeeder::class,
            RepurchaseProductSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}