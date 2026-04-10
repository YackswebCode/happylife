<?php
// app/Http/Controllers/LandingController.php

namespace App\Http\Controllers;

use App\Models\LandingProduct;
use App\Models\Setting;

class LandingController extends Controller
{
    public function index()
    {
        // Fetch products
        $products = LandingProduct::where('is_active', 1)
            ->orderBy('order', 'asc')
            ->latest()
            ->limit(8)
            ->get();

        // Fetch all settings – the corrected get() method will now work
        $settings = [
            'hero_badge'       => Setting::get('hero_badge', '🚀 Join The Revolution'),
            'hero_title'       => Setting::get('hero_title', 'Build Your <span class="text-warning">Financial Freedom</span> Empire'),
            'hero_subtitle'    => Setting::get('hero_subtitle', 'Happylife is the ultimate hybrid platform...'),
            'stat1_label'      => Setting::get('stat1_label', 'Active Members'),
            'stat1_value'      => Setting::get('stat1_value', '10K+'),
            'stat2_label'      => Setting::get('stat2_label', 'Paid in Earnings'),
            'stat2_value'      => Setting::get('stat2_value', '₦500M+'),
            'stat3_label'      => Setting::get('stat3_label', 'Support'),
            'stat3_value'      => Setting::get('stat3_value', '24/7'),
            'landing_features' => Setting::get('landing_features', []),
            'landing_steps'    => Setting::get('landing_steps', []),
            'team_members'     => Setting::get('team_members', []),
            'cta_title'        => Setting::get('cta_title', 'Ready to Transform Your Financial Future?'),
            'cta_subtitle'     => Setting::get('cta_subtitle', 'Join thousands of successful members...'),
            'cta_button_text'  => Setting::get('cta_button_text', 'Get Started Now'),
            'cta_button_link'  => Setting::get('cta_button_link', '/register'),
            'cta_secondary_text' => Setting::get('cta_secondary_text', 'Contact Support'),
            'cta_secondary_link' => Setting::get('cta_secondary_link', '/contact'),
            'cta_features'     => Setting::get('cta_features', []),
        ];

        // Provide default features/steps/team if DB arrays are empty
        if (empty($settings['landing_features'])) {
            $settings['landing_features'] = [
                ['title' => 'Binary MLM Network', 'description' => 'Build your network with our efficient binary structure...', 'icon' => 'bi-diagram-2'],
                ['title' => 'E-Commerce Mall', 'description' => 'Shop premium products and earn shopping bonuses...', 'icon' => 'bi-cart'],
                ['title' => 'Rank & Rewards', 'description' => 'Achieve prestigious ranks and win luxury rewards...', 'icon' => 'bi-trophy'],
                ['title' => 'VTU Services', 'description' => 'Buy airtime, data, pay bills and earn commissions...', 'icon' => 'bi-phone'],
            ];
        }

        if (empty($settings['landing_steps'])) {
            $settings['landing_steps'] = [
                ['title' => 'Register', 'description' => 'Sign up with a sponsor ID and select your preferred package', 'icon' => 'bi-person-plus'],
                ['title' => 'Build Network', 'description' => 'Refer others and build your downline using binary structure', 'icon' => 'bi-people'],
                ['title' => 'Earn Commissions', 'description' => 'Earn from multiple income streams including pairing and shopping bonuses', 'icon' => 'bi-cash-coin'],
                ['title' => 'Achieve Ranks', 'description' => 'Reach higher ranks and unlock luxurious rewards and bonuses', 'icon' => 'bi-trophy'],
            ];
        }

        if (empty($settings['team_members'])) {
            $settings['team_members'] = [
                ['name' => 'Happiness Ibrahim', 'role' => 'Founder & CEO', 'image' => 'images/team/team-1.jpg', 'bio' => 'Leads the overall vision...'],
                ['name' => 'Musa Abdulrahman', 'role' => 'Operations Manager', 'image' => 'images/team/team-2.jpg', 'bio' => 'Oversees daily operations...'],
                ['name' => 'Aisha Suleiman', 'role' => 'Marketing Lead', 'image' => 'images/team/team-3.jpg', 'bio' => 'Drives brand growth...'],
                ['name' => 'Yusuf Bello', 'role' => 'Technical Lead', 'image' => 'images/team/team-4.jpg', 'bio' => 'Maintains the platform...'],
            ];
        }

        return view('landing.index', compact('products', 'settings'));
    }

    public function faq()
    {
        $faqs = [
            ['question' => 'What is Happylife Multipurpose Int\'l?', 'answer' => 'Happylife is a Hybrid MLM + E-Commerce + Reward + VTU Services platform...'],
            // ... other FAQs
        ];
        return view('landing.faq', compact('faqs'));
    }
}