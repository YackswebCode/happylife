<?php

namespace App\Http\Controllers;

use App\Models\LandingProduct;

class LandingController extends Controller
{
    public function index()
    {
        $products = LandingProduct::where('is_active', 1)
            ->orderBy('order', 'asc')
            ->latest()
            ->limit(8)
            ->get();

        return view('landing.index', compact('products'));
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
                'answer' => 'We accept bank transfers and online payment gateways.'
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