<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name' => 'Sapphire',
                'price' => 6500,
                'pv' => 8,
                'product_entitlement' => 'Product worth ₦6,500',
                'direct_bonus_amount' => 1000,
                'pairing_cap' => 5000,
                'description' => 'Entry level package with basic benefits. Perfect for beginners.',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'Ohekem',
                'price' => 10500,
                'pv' => 12,
                'product_entitlement' => 'Product worth ₦10,500',
                'direct_bonus_amount' => 1500,
                'pairing_cap' => 10000,
                'description' => 'Popular package with good value. Great for serious starters.',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => 'Lifestyle',
                'price' => 54500,
                'pv' => 82,
                'product_entitlement' => 'Product worth ₦54,500',
                'direct_bonus_amount' => 10250,
                'pairing_cap' => 50000,
                'description' => 'Premium package with high PV. For established networkers.',
                'is_active' => true,
                'order' => 3
            ],
            [
                'name' => 'Business Guru',
                'price' => 272500,
                'pv' => 450,
                'product_entitlement' => 'Product worth ₦272,500',
                'direct_bonus_amount' => 56250,
                'pairing_cap' => 250000,
                'description' => 'Highest package for serious entrepreneurs and business leaders.',
                'is_active' => true,
                'order' => 4
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}