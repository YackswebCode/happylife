<?php

namespace Database\Seeders;

use App\Models\RepurchaseProduct;
use Illuminate\Database\Seeder;

class RepurchaseProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Health & Wellness
            [
                'name' => 'Multivitamin Complex',
                'category' => 'Health & Wellness',
                'price' => 8500,
                'pv_value' => 10,
                'stock' => 100,
                'image' => 'repurchase/vitamins.jpg',
                'description' => 'Premium multivitamin complex with essential vitamins and minerals for daily health support.',
                'is_active' => true,
                'sku' => 'HW-001'
            ],
            [
                'name' => 'Protein Powder',
                'category' => 'Health & Wellness',
                'price' => 12500,
                'pv_value' => 15,
                'stock' => 75,
                'image' => 'repurchase/protein.jpg',
                'description' => 'Whey protein powder for muscle recovery and fitness support.',
                'is_active' => true,
                'sku' => 'HW-002'
            ],
            [
                'name' => 'Omega-3 Fish Oil',
                'category' => 'Health & Wellness',
                'price' => 9500,
                'pv_value' => 12,
                'stock' => 120,
                'image' => 'repurchase/fish-oil.jpg',
                'description' => 'High-quality omega-3 fish oil capsules for heart and brain health.',
                'is_active' => true,
                'sku' => 'HW-003'
            ],

            // Food Items
            [
                'name' => 'Organic Honey',
                'category' => 'Food Items',
                'price' => 6500,
                'pv_value' => 8,
                'stock' => 200,
                'image' => 'repurchase/honey.jpg',
                'description' => 'Pure organic honey collected from natural sources.',
                'is_active' => true,
                'sku' => 'FI-001'
            ],
            [
                'name' => 'Premium Coffee',
                'category' => 'Food Items',
                'price' => 8500,
                'pv_value' => 10,
                'stock' => 150,
                'image' => 'repurchase/coffee.jpg',
                'description' => 'Arabica coffee beans from premium sources.',
                'is_active' => true,
                'sku' => 'FI-002'
            ],
            [
                'name' => 'Organic Green Tea',
                'category' => 'Food Items',
                'price' => 4500,
                'pv_value' => 6,
                'stock' => 300,
                'image' => 'repurchase/green-tea.jpg',
                'description' => 'Premium organic green tea with antioxidants.',
                'is_active' => true,
                'sku' => 'FI-003'
            ],

            // Fashion
            [
                'name' => 'Designer T-Shirt',
                'category' => 'Fashion',
                'price' => 7500,
                'pv_value' => 9,
                'stock' => 250,
                'image' => 'repurchase/tshirt.jpg',
                'description' => 'Premium quality designer t-shirt with unique prints.',
                'is_active' => true,
                'sku' => 'FA-001'
            ],
            [
                'name' => 'Leather Belt',
                'category' => 'Fashion',
                'price' => 10500,
                'pv_value' => 13,
                'stock' => 100,
                'image' => 'repurchase/belt.jpg',
                'description' => 'Genuine leather belt with premium buckle.',
                'is_active' => true,
                'sku' => 'FA-002'
            ],

            // Beauty Products
            [
                'name' => 'Vitamin C Serum',
                'category' => 'Beauty Products',
                'price' => 12500,
                'pv_value' => 15,
                'stock' => 150,
                'image' => 'repurchase/serum.jpg',
                'description' => 'Anti-aging vitamin C serum for glowing skin.',
                'is_active' => true,
                'sku' => 'BP-001'
            ],
            [
                'name' => 'Moisturizing Cream',
                'category' => 'Beauty Products',
                'price' => 8500,
                'pv_value' => 10,
                'stock' => 200,
                'image' => 'repurchase/cream.jpg',
                'description' => '24-hour moisturizing cream for all skin types.',
                'is_active' => true,
                'sku' => 'BP-002'
            ],

            // Home Essentials
            [
                'name' => 'Bed Sheet Set',
                'category' => 'Home Essentials',
                'price' => 18500,
                'pv_value' => 22,
                'stock' => 80,
                'image' => 'repurchase/bedsheet.jpg',
                'description' => 'Premium cotton bed sheet set with pillow cases.',
                'is_active' => true,
                'sku' => 'HE-001'
            ],
            [
                'name' => 'Kitchen Knife Set',
                'category' => 'Home Essentials',
                'price' => 22500,
                'pv_value' => 27,
                'stock' => 60,
                'image' => 'repurchase/knives.jpg',
                'description' => 'Professional kitchen knife set with wooden block.',
                'is_active' => true,
                'sku' => 'HE-002'
            ],

            // Kitchen Care
            [
                'name' => 'Non-stick Cookware Set',
                'category' => 'Kitchen Care',
                'price' => 32500,
                'pv_value' => 39,
                'stock' => 50,
                'image' => 'repurchase/cookware.jpg',
                'description' => 'Complete non-stick cookware set for modern kitchen.',
                'is_active' => true,
                'sku' => 'KC-001'
            ],
            [
                'name' => 'Food Storage Containers',
                'category' => 'Kitchen Care',
                'price' => 9500,
                'pv_value' => 12,
                'stock' => 180,
                'image' => 'repurchase/containers.jpg',
                'description' => 'BPA-free food storage containers set.',
                'is_active' => true,
                'sku' => 'KC-002'
            ],
        ];

        foreach ($products as $product) {
            RepurchaseProduct::create($product);
        }
    }
}