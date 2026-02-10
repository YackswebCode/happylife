@extends('layouts.app')

@section('title', 'Happylife Multipurpose Int\'l - Hybrid MLM Platform')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
    <!-- SVG Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative py-5 py-lg-6">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="mb-4">
                    <span class="badge bg-white text-dark px-4 py-2 rounded-pill fw-semibold mb-3">
                        🚀 Join The Revolution
                    </span>
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Build Your <span class="text-warning">Financial Freedom</span> Empire
                    </h1>
                    <p class="lead text-white opacity-75 mb-5">
                        Happylife is the ultimate hybrid platform combining MLM networking, e-commerce shopping, 
                        reward systems, and VTU services into one powerful income-generating ecosystem.
                    </p>
                </div>
                
                <div class="d-flex flex-column flex-sm-row gap-3 mb-5">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <span>Start Earning Today</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('packages') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-3 border-2">
                        Explore Packages
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="row g-4">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h2 fw-bold text-white mb-1">10K+</div>
                            <div class="text-white opacity-75 small">Active Members</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h2 fw-bold text-white mb-1">₦500M+</div>
                            <div class="text-white opacity-75 small">Paid in Earnings</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h2 fw-bold text-white mb-1">24/7</div>
                            <div class="text-white opacity-75 small">Support</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <!-- Hero SVG Illustration -->
                <div class="position-relative">
                    <div class="position-relative">
                        <svg class="w-100" viewBox="0 0 600 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background Circle -->
                            <circle cx="300" cy="200" r="180" fill="url(#gradient1)" fill-opacity="0.2"/>
                            
                            <!-- Network Nodes -->
                            <circle cx="300" cy="200" r="8" fill="#E63323" stroke="#fff" stroke-width="3"/>
                            
                            <!-- Node 1 -->
                            <circle cx="150" cy="150" r="6" fill="#1FA3C4"/>
                            <circle cx="150" cy="250" r="6" fill="#1FA3C4"/>
                            <circle cx="300" cy="100" r="6" fill="#1FA3C4"/>
                            <circle cx="300" cy="300" r="6" fill="#1FA3C4"/>
                            <circle cx="450" cy="150" r="6" fill="#1FA3C4"/>
                            <circle cx="450" cy="250" r="6" fill="#1FA3C4"/>
                            
                            <!-- Connecting Lines -->
                            <line x1="300" y1="200" x2="150" y2="150" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="150" y2="250" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="300" y2="100" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="300" y2="300" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="450" y2="150" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="450" y2="250" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            
                            <!-- Floating Elements -->
                            <circle cx="100" cy="100" r="4" fill="#E63323" fill-opacity="0.7">
                                <animate attributeName="cy" values="100;90;100" dur="3s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="500" cy="100" r="4" fill="#3DB7D6" fill-opacity="0.7">
                                <animate attributeName="cx" values="500;510;500" dur="4s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="100" cy="300" r="4" fill="#3DB7D6" fill-opacity="0.7">
                                <animate attributeName="cx" values="100;90;100" dur="3.5s" repeatCount="indefinite"/>
                            </circle>
                            
                            <!-- Gradient Definition -->
                            <defs>
                                <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#E63323"/>
                                    <stop offset="100%" stop-color="#1FA3C4"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-6 position-relative bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Multiple <span class="text-danger">Income Streams</span>, One Platform</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Diversify your earnings with our comprehensive ecosystem designed for maximum profitability
            </p>
        </div>
        
        <div class="row g-5">
            <!-- Feature 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-lg rounded-4 h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-4 mx-auto">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="32" cy="32" r="30" fill="#E63323" fill-opacity="0.1"/>
                                <path d="M42 24L32 34L22 24" stroke="#E63323" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M42 40L32 50L22 40" stroke="#E63323" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="32" cy="32" r="4" fill="#E63323"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Binary MLM Network</h4>
                        <p class="text-muted mb-0">
                            Build your network with our efficient binary structure and earn from every level of your downline.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-lg rounded-4 h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-4 mx-auto">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="32" cy="32" r="30" fill="#1FA3C4" fill-opacity="0.1"/>
                                <rect x="18" y="20" width="28" height="24" rx="3" stroke="#1FA3C4" stroke-width="2"/>
                                <circle cx="32" cy="28" r="2" fill="#1FA3C4"/>
                                <path d="M24 36H40" stroke="#1FA3C4" stroke-width="2" stroke-linecap="round"/>
                                <path d="M24 40H34" stroke="#1FA3C4" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">E-Commerce Mall</h4>
                        <p class="text-muted mb-0">
                            Shop premium products and earn shopping bonuses on every purchase in our exclusive mall.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-lg rounded-4 h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-4 mx-auto">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="32" cy="32" r="30" fill="#FFD700" fill-opacity="0.1"/>
                                <path d="M32 22L38 34L48 36L38 40L32 52L26 40L16 36L26 34L32 22Z" stroke="#FFA500" stroke-width="2" fill="#FFD700" fill-opacity="0.3"/>
                                <circle cx="32" cy="32" r="4" fill="#FFA500"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Rank & Rewards</h4>
                        <p class="text-muted mb-0">
                            Achieve prestigious ranks and win luxury rewards including cars, trips, and cash bonuses.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-lg rounded-4 h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-4 mx-auto">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="32" cy="32" r="30" fill="#3DB7D6" fill-opacity="0.1"/>
                                <path d="M24 28H40M24 36H40M24 44H40" stroke="#3DB7D6" stroke-width="2" stroke-linecap="round"/>
                                <path d="M48 24H16C14.8954 24 14 24.8954 14 26V46C14 47.1046 14.8954 48 16 48H48C49.1046 48 50 47.1046 50 46V26C50 24.8954 49.1046 24 48 24Z" stroke="#3DB7D6" stroke-width="2"/>
                                <circle cx="32" cy="32" r="4" fill="#3DB7D6"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">VTU Services</h4>
                        <p class="text-muted mb-0">
                            Buy airtime, data, pay bills and earn commissions from every VTU transaction.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Membership Packages -->
<section class="py-6 bg-light position-relative">
    <!-- Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-03">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#E63323" d="M0,160L48,176C96,192,192,224,288,208C384,192,480,128,576,112C672,96,768,128,864,144C960,160,1056,160,1152,144C1248,128,1344,96,1392,80L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Choose Your <span class="text-danger">Membership</span> Level</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Select the package that aligns with your financial goals and start building your empire today
            </p>
        </div>
        
        <div class="row g-4 justify-content-center">
            @foreach($packages as $index => $package)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-lg rounded-4 h-100 {{ $index == 1 ? 'premium-package' : '' }}">
                    @if($index == 1)
                    <div class="position-absolute top-0 start-50 translate-middle mt-n3">
                        <span class="badge bg-danger px-4 py-2 rounded-pill fw-bold">
                            Most Popular
                        </span>
                    </div>
                    @endif
                    
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="h3 fw-bold mb-2">{{ $package->name }}</h3>
                            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                    {{ $package->pv }} PV
                                </span>
                            </div>
                            
                            <!-- Package Icon -->
                            <div class="package-icon mx-auto mb-4">
                                @switch($index)
                                    @case(0)
                                        <i class="bi bi-gem text-primary" style="font-size: 2.5rem;"></i>
                                        @break
                                    @case(1)
                                        <i class="bi bi-star-fill text-warning" style="font-size: 2.5rem;"></i>
                                        @break
                                    @case(2)
                                        <i class="bi bi-trophy text-success" style="font-size: 2.5rem;"></i>
                                        @break
                                    @case(3)
                                        <i class="bi bi-award text-danger" style="font-size: 2.5rem;"></i>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">Product worth ₦{{ number_format($package->price, 2) }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">Direct Referral Bonus</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">Pairing Bonus Eligible</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">Full Platform Access</span>
                            </li>
                        </ul>
                        
                        <a href="{{ route('register') }}" class="btn btn-lg w-100 py-3 fw-bold {{ $index == 1 ? 'btn-danger' : 'btn-outline-dark' }}">
                            Select Package
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('packages') }}" class="btn btn-outline-danger btn-lg px-5 py-3 fw-bold rounded-pill">
                Compare All Packages <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">How It <span class="text-danger">Works</span></h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                Follow these simple steps to start earning with Happylife
            </p>
        </div>
        
        <div class="row g-5">
            @php
                $steps = [
                    ['icon' => 'bi-person-plus', 'title' => 'Register', 'desc' => 'Sign up with a sponsor ID and select your preferred package'],
                    ['icon' => 'bi-people', 'title' => 'Build Network', 'desc' => 'Refer others and build your downline using binary structure'],
                    ['icon' => 'bi-cash-coin', 'title' => 'Earn Commissions', 'desc' => 'Earn from multiple income streams including pairing and shopping bonuses'],
                    ['icon' => 'bi-trophy', 'title' => 'Achieve Ranks', 'desc' => 'Reach higher ranks and unlock luxurious rewards and bonuses'],
                ];
            @endphp
            
            @foreach($steps as $index => $step)
            <div class="col-md-6 col-lg-3">
                <div class="step-card text-center position-relative">
                    <div class="step-number mb-4">
                        <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="h4 fw-bold mb-0">{{ $index + 1 }}</span>
                        </div>
                    </div>
                    <div class="step-icon mb-4">
                        <i class="bi {{ $step['icon'] }} text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">{{ $step['title'] }}</h4>
                    <p class="text-muted">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-6 position-relative overflow-hidden" style="background: linear-gradient(135deg, #E63323 0%, #d6281a 100%);">
    <!-- Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,128C672,96,768,64,864,74.7C960,85,1056,139,1152,138.7C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="display-4 fw-bold text-white mb-4">
                    Ready to Transform Your Financial Future?
                </h2>
                <p class="lead text-white opacity-75 mb-5 mx-auto" style="max-width: 700px;">
                    Join thousands of successful members who have achieved financial independence through our proven system. 
                    Your journey to wealth starts here.
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-4 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-3">
                        <span>Get Started Now</span>
                        <i class="bi bi-arrow-right-circle-fill" style="font-size: 1.2rem;"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-3 border-2">
                        Contact Support
                    </a>
                </div>
                
                <div class="mt-5">
                    <div class="row g-4 justify-content-center">
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-shield-check me-2"></i>
                                <span>Secure Platform</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-clock-history me-2"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-cash-stack me-2"></i>
                                <span>Instant Withdrawals</span>
                            </div>
                        </div>
                    </div>
                </div>
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
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    .premium-package {
        border: 3px solid #E63323 !important;
        transform: scale(1.05);
    }
    
    .step-card {
        padding: 2rem;
        border-radius: 1rem;
        background: #fff;
        transition: all 0.3s ease;
    }
    
    .step-card:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    }
    
    .package-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        margin-bottom: 1.5rem;
    }
    
    .opacity-03 {
        opacity: 0.03;
    }
    
    .opacity-10 {
        opacity: 0.1;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .premium-package {
            transform: scale(1);
            margin-top: 1rem;
        }
        
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
        
        .step-card {
            padding: 1.5rem;
            margin-bottom: 1rem;
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
        
        .card {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection