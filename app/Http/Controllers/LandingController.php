<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LandingController extends Controller
{
    public function index()
    {
        if (class_exists('App\Models\Product')) {
            $products = Product::latest()->limit(8)->get();
        } else {
            // Fallback data with image paths (placeholders)
            $products = collect([
                (object) [
                    'name'  => 'Starter Kit',
                    'pv'    => 10,
                    'price' => 6500,
                    'image' => 'images/products/starter-kit.jpg'
                ],
                (object) [
                    'name'  => 'Ohekem Pack',
                    'pv'    => 16,
                    'price' => 10500,
                    'image' => 'images/products/ohekem-pack.jpg'
                ],
                (object) [
                    'name'  => 'Emerald Pack',
                    'pv'    => 100,
                    'price' => 54500,
                    'image' => 'images/products/emerald-pack.jpg'
                ],
                (object) [
                    'name'  => 'Achievers Pack',
                    'pv'    => 244,
                    'price' => 132500,
                    'image' => 'images/products/achievers-pack.jpg'
                ],
                (object) [
                    'name'  => 'Lifestyle Pack',
                    'pv'    => 500,
                    'price' => 265500,
                    'image' => 'images/products/lifestyle-pack.jpg'
                ],
            ]);
        }

        // Optional featured products
        if (class_exists('App\Models\LandingProduct')) {
            $featuredProducts = \App\Models\LandingProduct::where('is_active', true)->limit(6)->get();
        } else {
            $featuredProducts = collect();
        }
            
        return view('landing.index', compact('products', 'featuredProducts'));
    }

    public function about()
    {
        return view('landing.about');
    }

   

    public function contact()
    {
        return view('landing.contact');
    }

    public function faq()
    {
        $faqs = [
            [
                'question' => 'What is Happylife Multipurpose Int\'l?',
                'answer' => 'Happylife is a Hybrid MLM + E-Commerce + Reward + VTU Services platform where users can earn through multiple income streams including referrals, shopping bonuses, rank achievements, and VTU services.'
            ],
            [
                'question' => 'How do I register?',
                'answer' => 'You need a sponsor ID to register. Visit the registration page, fill in your details, select a package, and choose your preferred product.'
            ],
            [
                'question' => 'What payment methods are accepted?',
                'answer' => 'We accept bank transfers, online payment gateways, and upline wallet payments.'
            ],
            [
                'question' => 'How do I withdraw my earnings?',
                'answer' => 'You can withdraw from your Commission Bonus Wallet, Rank Award Wallet, or Shopping Bonus Wallet. Minimum withdrawal is ₦2,000 per day.'
            ],
            [
                'question' => 'What is the Binary Structure?',
                'answer' => 'Our system uses a binary structure where each member can refer two direct downlines (left and right). Earnings are generated through pairing bonuses.'
            ],
            [
                'question' => 'Can I upgrade my package?',
                'answer' => 'Yes, you can upgrade your package at any time from your member dashboard.'
            ]
        ];
        
        return view('landing.faq', compact('faqs'));
    }
}