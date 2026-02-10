@extends('layouts.app')

@section('title', 'Frequently Asked Questions - Happylife Multipurpose Int\'l')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
    <!-- SVG Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#1FA3C4" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative py-5 py-lg-6">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1 class="display-4 fw-bold text-white mb-4">Frequently Asked <span class="text-warning">Questions</span></h1>
                <p class="lead text-white opacity-75 mb-0">
                    Find answers to common questions about our platform and services
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Search -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <div class="faq-search-icon mb-4">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="36" fill="url(#searchGradient)" stroke="#E63323" stroke-width="2"/>
                            <path d="M55 55L45 45M50 35C50 43.2843 43.2843 50 35 50C26.7157 50 20 43.2843 20 35C20 26.7157 26.7157 20 35 20C43.2843 20 50 26.7157 50 35Z" stroke="#1FA3C4" stroke-width="3" stroke-linecap="round"/>
                            <defs>
                                <linearGradient id="searchGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#f8f9fa"/>
                                    <stop offset="100%" stop-color="#e9ecef"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h2 class="h3 fw-bold mb-3">Quick Search</h2>
                    <p class="text-muted mb-4">Search through our FAQ database to find answers quickly</p>
                </div>
                
                <div class="input-group input-group-lg mb-5">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="faqSearch" placeholder="Search for questions about packages, withdrawals, commissions, etc...">
                    <button class="btn btn-danger" type="button">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Categories -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Browse by <span class="text-danger">Category</span></h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Select a category to quickly find answers related to specific topics
            </p>
        </div>
        
        <div class="row g-4 mb-6">
            @php
                $categories = [
                    ['icon' => 'bi-people', 'title' => 'Registration', 'count' => 8, 'color' => 'danger'],
                    ['icon' => 'bi-cash-coin', 'title' => 'Earnings', 'count' => 12, 'color' => 'success'],
                    ['icon' => 'bi-wallet2', 'title' => 'Withdrawals', 'count' => 6, 'color' => 'primary'],
                    ['icon' => 'bi-cart', 'title' => 'Shopping Mall', 'count' => 5, 'color' => 'warning'],
                    ['icon' => 'bi-trophy', 'title' => 'Ranks & Rewards', 'count' => 7, 'color' => 'info'],
                    ['icon' => 'bi-phone', 'title' => 'VTU Services', 'count' => 4, 'color' => 'secondary'],
                ];
            @endphp
            
            @foreach($categories as $category)
            <div class="col-md-4 col-lg-2">
                <div class="category-card text-center p-4 rounded-4 border-0 shadow-sm">
                    <div class="category-icon mb-3">
                        <div class="rounded-circle bg-{{ $category['color'] }} bg-opacity-10 text-{{ $category['color'] }} p-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi {{ $category['icon'] }} fs-2"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $category['title'] }}</h5>
                    <div class="badge bg-light text-dark">{{ $category['count'] }} Questions</div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- FAQ Accordion -->
        <div class="row g-5">
            <!-- General Questions -->
            <div class="col-lg-6">
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 me-3">
                                <i class="bi bi-question-circle fs-2"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="h1 fw-bold mb-1">General Questions</h2>
                            <p class="text-muted">Common questions about our platform</p>
                        </div>
                    </div>
                    
                    <div class="accordion" id="generalAccordion">
                        @foreach($faqs as $index => $faq)
                        <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                            <h2 class="accordion-header" id="generalHeading{{ $index }}">
                                <button class="accordion-button collapsed rounded-4 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#generalCollapse{{ $index }}" aria-expanded="false" aria-controls="generalCollapse{{ $index }}">
                                    <span class="fw-bold me-3">{{ $faq['question'] }}</span>
                                </button>
                            </h2>
                            <div id="generalCollapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="generalHeading{{ $index }}" data-bs-parent="#generalAccordion">
                                <div class="accordion-body pt-0">
                                    <div class="answer-content p-4">
                                        <p class="mb-0">{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Technical & Earnings -->
            <div class="col-lg-6">
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3">
                                <i class="bi bi-gear fs-2"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="h1 fw-bold mb-1">Technical & Earnings</h2>
                            <p class="text-muted">Questions about systems and income</p>
                        </div>
                    </div>
                    
                    <div class="accordion" id="technicalAccordion">
                        @php
                            $technicalFaqs = [
                                [
                                    'question' => 'What is the Binary Structure?',
                                    'answer' => 'Our system uses a binary structure where each member can place two direct referrals (left and right). Earnings are generated through pairing bonuses when you have 40 PV on both sides. This structure allows for exponential growth as each member builds two strong legs.'
                                ],
                                [
                                    'question' => 'How do I earn commissions?',
                                    'answer' => 'You can earn through multiple streams:',
                                    'list' => [
                                        'Direct Sponsor Bonus (25% of package value)',
                                        'Pairing Bonus (₦1,500 per 40 PV pair)',
                                        'Upgrade Bonus (shared with upline)',
                                        'Indirect Sponsor Bonus (10% up to 2nd generation)',
                                        'Shopping Bonus (₦250 per repurchase)',
                                        'Rank Awards (cash and prizes)'
                                    ]
                                ],
                                [
                                    'question' => 'What are the wallet types?',
                                    'answer' => 'We have 4 specialized wallets:',
                                    'list' => [
                                        '<strong>Commission Bonus Wallet:</strong> For referral, indirect, upgrade, and pairing bonuses',
                                        '<strong>Registration Wallet:</strong> For paying for downline registrations',
                                        '<strong>Rank Award Wallet:</strong> For storing rank bonuses',
                                        '<strong>Shopping Bonus Wallet:</strong> For repurchase bonuses'
                                    ]
                                ],
                                [
                                    'question' => 'What is the withdrawal process?',
                                    'answer' => 'Our withdrawal process is simple and secure:',
                                    'list' => [
                                        'Minimum withdrawal: ₦2,000 per day',
                                        'Maximum withdrawal: ₦2,500,000 per day',
                                        'Admin charge: 2% on all withdrawals',
                                        'Rank bonuses are withdrawn separately',
                                        'Withdrawals are processed within 24-48 hours'
                                    ]
                                ],
                                [
                                    'question' => 'What VTU services are available?',
                                    'answer' => 'You can purchase various services:',
                                    'list' => [
                                        'Airtime for all networks',
                                        'Data bundles for all major providers',
                                        'Cable TV subscriptions (DSTV, GOTV, etc.)',
                                        'Electricity bills (prepaid and postpaid)',
                                        'Other utility payments'
                                    ],
                                    'note' => 'All VTU services are charged from your Commission Bonus Wallet.'
                                ]
                            ];
                        @endphp
                        
                        @foreach($technicalFaqs as $index => $faq)
                        <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                            <h2 class="accordion-header" id="technicalHeading{{ $index }}">
                                <button class="accordion-button collapsed rounded-4 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#technicalCollapse{{ $index }}" aria-expanded="false" aria-controls="technicalCollapse{{ $index }}">
                                    <span class="fw-bold me-3">{{ $faq['question'] }}</span>
                                </button>
                            </h2>
                            <div id="technicalCollapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="technicalHeading{{ $index }}" data-bs-parent="#technicalAccordion">
                                <div class="accordion-body pt-0">
                                    <div class="answer-content p-4">
                                        <p>{{ $faq['answer'] }}</p>
                                        
                                        @if(isset($faq['list']))
                                        <ul class="{{ isset($faq['note']) ? 'mb-3' : 'mb-0' }}">
                                            @foreach($faq['list'] as $item)
                                            <li class="mb-2">{!! $item !!}</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                        
                                        @if(isset($faq['note']))
                                        <div class="alert alert-info bg-light border-0 mt-3">
                                            <i class="bi bi-info-circle me-2"></i> {{ $faq['note'] }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ Visualization -->
        <div class="row justify-content-center mb-6">
            <div class="col-lg-10">
                <div class="faq-visualization rounded-4 p-4 shadow-sm border">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="fw-bold mb-4">Understanding Our Ecosystem</h3>
                            <p class="text-muted mb-4">
                                Our platform integrates multiple income streams into one seamless experience. 
                                From MLM networking to e-commerce and VTU services, everything works together 
                                to maximize your earning potential.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">Binary MLM</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">E-Commerce</span>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">VTU Services</span>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Rank Rewards</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <svg width="300" height="200" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Center Circle -->
                                    <circle cx="150" cy="100" r="40" fill="url(#centerGradient)" stroke="#E63323" stroke-width="2"/>
                                    
                                    <!-- Satellite Circles -->
                                    <circle cx="70" cy="70" r="25" fill="url(#satellite1)" stroke="#1FA3C4" stroke-width="2"/>
                                    <circle cx="230" cy="70" r="25" fill="url(#satellite2)" stroke="#3DB7D6" stroke-width="2"/>
                                    <circle cx="70" cy="130" r="25" fill="url(#satellite3)" stroke="#FFD700" stroke-width="2"/>
                                    <circle cx="230" cy="130" r="25" fill="url(#satellite4)" stroke="#28a745" stroke-width="2"/>
                                    
                                    <!-- Connecting Lines -->
                                    <line x1="150" y1="100" x2="95" y2="70" stroke="#E63323" stroke-width="2" stroke-dasharray="4 4"/>
                                    <line x1="150" y1="100" x2="205" y2="70" stroke="#E63323" stroke-width="2" stroke-dasharray="4 4"/>
                                    <line x1="150" y1="100" x2="95" y2="130" stroke="#E63323" stroke-width="2" stroke-dasharray="4 4"/>
                                    <line x1="150" y1="100" x2="205" y2="130" stroke="#E63323" stroke-width="2" stroke-dasharray="4 4"/>
                                    
                                    <!-- Labels -->
                                    <text x="70" y="70" text-anchor="middle" fill="#1FA3C4" font-weight="bold" font-size="8">MLM</text>
                                    <text x="230" y="70" text-anchor="middle" fill="#3DB7D6" font-weight="bold" font-size="8">E-Com</text>
                                    <text x="70" y="130" text-anchor="middle" fill="#FFD700" font-weight="bold" font-size="8">Ranks</text>
                                    <text x="230" y="130" text-anchor="middle" fill="#28a745" font-weight="bold" font-size="8">VTU</text>
                                    
                                    <defs>
                                        <linearGradient id="centerGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#E63323"/>
                                            <stop offset="100%" stop-color="#d6281a"/>
                                        </linearGradient>
                                        <linearGradient id="satellite1" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#1FA3C4"/>
                                            <stop offset="100%" stop-color="#3DB7D6"/>
                                        </linearGradient>
                                        <linearGradient id="satellite2" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#3DB7D6"/>
                                            <stop offset="100%" stop-color="#1FA3C4"/>
                                        </linearGradient>
                                        <linearGradient id="satellite3" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#FFD700"/>
                                            <stop offset="100%" stop-color="#FFA500"/>
                                        </linearGradient>
                                        <linearGradient id="satellite4" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#28a745"/>
                                            <stop offset="100%" stop-color="#20c997"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-6 position-relative overflow-hidden" style="background: linear-gradient(135deg, #E63323 0%, #d6281a 100%);">
    <!-- Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#E63323" fill-opacity="1" d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,128C672,96,768,64,864,74.7C960,85,1056,139,1152,138.7C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="display-4 fw-bold text-white mb-4">
                    Still Have Questions?
                </h2>
                <p class="lead text-white opacity-75 mb-5 mx-auto" style="max-width: 700px;">
                    Can't find the answer you're looking for? Our dedicated support team is ready to help you 
                    with any questions about our platform.
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-4 justify-content-center">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-3">
                        <i class="bi bi-headset" style="font-size: 1.2rem;"></i>
                        <span>Contact Support</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-3 border-2">
                        Get Started Now
                    </a>
                </div>
                
                <div class="mt-5">
                    <div class="row g-4 justify-content-center">
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-check-circle me-2"></i>
                                <span>24/7 Email Support</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-check-circle me-2"></i>
                                <span>Live Chat Available</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-check-circle me-2"></i>
                                <span>Phone Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Resources -->
<section class="py-6 bg-light">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Quick <span class="text-danger">Resources</span></h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Explore these additional resources to learn more about our platform
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <a href="{{ route('packages') }}" class="resource-card card border-0 shadow-sm text-decoration-none h-100">
                    <div class="card-body p-5 text-center">
                        <div class="resource-icon mb-4">
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-4 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-box-seam fs-1"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">Membership Packages</h4>
                        <p class="text-muted mb-0">
                            Learn about our four membership levels and choose the one that fits your goals
                        </p>
                        <div class="mt-4">
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                View Details <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="{{ route('about') }}" class="resource-card card border-0 shadow-sm text-decoration-none h-100">
                    <div class="card-body p-5 text-center">
                        <div class="resource-icon mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-4 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-info-circle fs-1"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">About Our Platform</h4>
                        <p class="text-muted mb-0">
                            Discover our mission, vision, and the technology behind our success
                        </p>
                        <div class="mt-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                Learn More <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="{{ route('contact') }}" class="resource-card card border-0 shadow-sm text-decoration-none h-100">
                    <div class="card-body p-5 text-center">
                        <div class="resource-icon mb-4">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-4 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-headset fs-1"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">Contact Support</h4>
                        <p class="text-muted mb-0">
                            Get direct assistance from our dedicated support team
                        </p>
                        <div class="mt-4">
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                Get Help <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Custom Styles */
    .py-6 {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }
    
    .py-lg-6 {
        padding-top: 5rem !important;
        padding-bottom: 5rem !important;
    }
    
    .faq-search-icon {
        transition: transform 0.3s ease;
    }
    
    .faq-search-icon:hover {
        transform: scale(1.1);
    }
    
    .category-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .accordion-button {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-size: 1.1rem;
        color: #333;
        transition: all 0.3s ease;
    }
    
    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #E63323 0%, #d6281a 100%);
        color: white;
        box-shadow: none;
    }
    
    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23E63323'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transition: transform 0.3s ease;
    }
    
    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    
    .answer-content {
        background: #fff;
        border-left: 4px solid #E63323;
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .faq-visualization {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .resource-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .resource-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    /* Search Input Focus */
    #faqSearch:focus {
        border-color: #E63323;
        box-shadow: 0 0 0 0.25rem rgba(230, 51, 35, 0.25);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }
        
        .display-5 {
            font-size: 2rem;
        }
        
        .py-6 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }
        
        .category-card {
            margin-bottom: 1.5rem;
        }
        
        .accordion-button {
            font-size: 1rem;
            padding: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .display-4 {
            font-size: 2rem;
        }
        
        .display-5 {
            font-size: 1.75rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        
        .faq-visualization {
            padding: 1.5rem !important;
        }
        
        .resource-card {
            margin-bottom: 1.5rem;
        }
    }
</style>

<script>
    // FAQ Search Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('faqSearch');
        const accordionButtons = document.querySelectorAll('.accordion-button');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            accordionButtons.forEach(button => {
                const question = button.textContent.toLowerCase();
                const collapseId = button.getAttribute('data-bs-target');
                const collapseElement = document.querySelector(collapseId);
                
                if (searchTerm === '') {
                    // Show all FAQs
                    button.closest('.accordion-item').style.display = '';
                } else if (question.includes(searchTerm)) {
                    // Show matching FAQs
                    button.closest('.accordion-item').style.display = '';
                    
                    // Open matching FAQ
                    const bsCollapse = new bootstrap.Collapse(collapseElement, {
                        toggle: false
                    });
                    bsCollapse.show();
                } else {
                    // Hide non-matching FAQs
                    button.closest('.accordion-item').style.display = 'none';
                }
            });
        });
        
        // Category card click
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                const category = this.querySelector('h5').textContent.toLowerCase();
                searchInput.value = category;
                searchInput.dispatchEvent(new Event('input'));
                
                // Scroll to FAQ section
                document.querySelector('.accordion').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
@endsection