<?php

namespace Database\Seeders;

use App\Models\LandingProduct;
use Illuminate\Database\Seeder;

class LandingProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Premium Wellness Kit',
                'image' => 'products/wellness-kit.jpg',
                'description' => 'Complete health and wellness package with essential supplements, vitamins, and herbal extracts for optimal health.',
                'display_price' => 19999.99,
                'category' => 'Health & Wellness',
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Executive Business Bag',
                'image' => 'products/business-bag.jpg',
                'description' => 'Luxury leather business bag with multiple compartments, RFID protection, and premium stitching for the modern professional.',
                'display_price' => 34999.99,
                'category' => 'Fashion',
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Smart Home Device',
                'image' => 'products/smart-home.jpg',
                'description' => 'Latest smart home assistant with voice control, home automation, and AI capabilities for modern living.',
                'display_price' => 25999.99,
                'category' => 'Electronics',
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Organic Food Basket',
                'image' => 'products/food-basket.jpg',
                'description' => 'Assortment of organic foods, healthy snacks, and premium ingredients for a nutritious lifestyle.',
                'display_price' => 14999.99,
                'category' => 'Food Items',
                'order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Beauty Care Package',
                'image' => 'products/beauty-care.jpg',
                'description' => 'Complete skincare and beauty care products set including cleansers, toners, moisturizers, and serums.',
                'display_price' => 12999.99,
                'category' => 'Beauty Products',
                'order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'Fitness Equipment Set',
                'image' => 'products/fitness-set.jpg',
                'description' => 'Home gym equipment set including resistance bands, dumbbells, yoga mat, and workout guide for complete fitness.',
                'display_price' => 45999.99,
                'category' => 'Fitness',
                'order' => 6,
                'is_active' => true
            ],
            [
                'name' => 'Kitchen Care Essentials',
                'image' => 'products/kitchen-care.jpg',
                'description' => 'Premium kitchen utensils, cookware, and storage solutions for modern kitchen needs.',
                'display_price' => 23999.99,
                'category' => 'Kitchen Care',
                'order' => 7,
                'is_active' => true
            ],
            [
                'name' => 'Home Essentials Bundle',
                'image' => 'products/home-essentials.jpg',
                'description' => 'Complete home essentials including bedding, towels, organizers, and decor items for comfortable living.',
                'display_price' => 28999.99,
                'category' => 'Home Essentials',
                'order' => 8,
                'is_active' => true
            ],
        ];

        foreach ($products as $product) {
            LandingProduct::create($product);
        }
    }
}