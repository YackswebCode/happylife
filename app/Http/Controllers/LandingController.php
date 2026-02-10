<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Check if Package model exists
        if (class_exists('App\Models\Package')) {
            $packages = \App\Models\Package::where('is_active', true)->get();
        } else {
            // Fallback data if model doesn't exist yet
            $packages = collect([
                (object) [
                    'name' => 'Sapphire',
                    'pv' => 8,
                    'price' => 6500,
                    'product_entitlement' => 'Product worth ₦6,500'
                ],
                (object) [
                    'name' => 'Ohekem',
                    'pv' => 12,
                    'price' => 10500,
                    'product_entitlement' => 'Product worth ₦10,500'
                ],
                (object) [
                    'name' => 'Lifestyle',
                    'pv' => 82,
                    'price' => 54500,
                    'product_entitlement' => 'Product worth ₦54,500'
                ],
                (object) [
                    'name' => 'Business Guru',
                    'pv' => 450,
                    'price' => 272500,
                    'product_entitlement' => 'Product worth ₦272,500'
                ],
            ]);
        }

        // Check if LandingProduct model exists
        if (class_exists('App\Models\LandingProduct')) {
            $featuredProducts = \App\Models\LandingProduct::where('is_active', true)
                ->limit(6)
                ->get();
        } else {
            $featuredProducts = collect();
        }
            
        return view('landing.index', compact('packages', 'featuredProducts'));
    }

    public function about()
    {
        return view('landing.about');
    }

    public function packages()
    {
        // Check if Package model exists
        if (class_exists('App\Models\Package')) {
            $packages = \App\Models\Package::where('is_active', true)->get();
        } else {
            // Fallback data if model doesn't exist yet
            $packages = collect([
                (object) [
                    'name' => 'Sapphire',
                    'pv' => 8,
                    'price' => 6500,
                    'product_entitlement' => 'Product worth ₦6,500'
                ],
                (object) [
                    'name' => 'Ohekem',
                    'pv' => 12,
                    'price' => 10500,
                    'product_entitlement' => 'Product worth ₦10,500'
                ],
                (object) [
                    'name' => 'Lifestyle',
                    'pv' => 82,
                    'price' => 54500,
                    'product_entitlement' => 'Product worth ₦54,500'
                ],
                (object) [
                    'name' => 'Business Guru',
                    'pv' => 450,
                    'price' => 272500,
                    'product_entitlement' => 'Product worth ₦272,500'
                ],
            ]);
        }
        
        return view('landing.packages', compact('packages'));
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