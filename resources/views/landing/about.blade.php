@extends('layouts.app')

@section('title', 'About Us - Happylife Multipurpose Int\'l')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
    <!-- SVG Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative py-5 py-lg-6">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1 class="display-4 fw-bold text-white mb-4">About <span class="text-warning">Happylife</span></h1>
                <p class="lead text-white opacity-75 mb-0">
                    Revolutionizing Income Opportunities Through Hybrid MLM + E-Commerce + Reward + VTU Services
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="mb-4">
                    <span class="badge bg-danger bg-opacity-10 text-danger px-4 py-2 rounded-pill fw-semibold mb-3">
                        Our Mission
                    </span>
                    <h2 class="display-5 fw-bold mb-4">
                        Empowering <span class="text-danger">Financial Independence</span>
                    </h2>
                    <p class="lead text-muted mb-4">
                        To create a platform where everyone can achieve financial freedom through multiple, 
                        sustainable income streams.
                    </p>
                    <p class="text-muted mb-5">
                        Happylife Multipurpose Int'l was founded with the mission to empower individuals by 
                        combining the best of MLM networking, e-commerce shopping, reward systems, and utility 
                        services in one comprehensive platform.
                    </p>
                    
                    <!-- Key Features -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 me-3">
                                        <i class="bi bi-cash-stack fs-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Multiple Income Streams</h5>
                                    <p class="text-muted small mb-0">Earn from referrals, shopping, ranks, and VTU services</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3">
                                        <i class="bi bi-graph-up-arrow fs-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Exponential Growth</h5>
                                    <p class="text-muted small mb-0">Binary structure enables rapid network expansion</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3">
                                        <i class="bi bi-shield-check fs-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Secure Platform</h5>
                                    <p class="text-muted small mb-0">Bank-level security for all transactions and data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 me-3">
                                        <i class="bi bi-award fs-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Proven Success</h5>
                                    <p class="text-muted small mb-0">Thousands of members achieving financial goals</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <!-- About Illustration -->
                <div class="position-relative">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="position-relative">
                                <!-- Background Pattern -->
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-5"></div>
                                
                                <!-- Main Illustration -->
                                <div class="position-relative p-5">
                                    <div class="text-center">
                                        <svg width="400" height="300" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-100">
                                            <!-- Building Structure -->
                                            <rect x="100" y="100" width="200" height="150" rx="10" fill="url(#buildingGradient)" stroke="#1FA3C4" stroke-width="2"/>
                                            
                                            <!-- Windows -->
                                            <rect x="120" y="120" width="40" height="40" rx="5" fill="#3DB7D6"/>
                                            <rect x="170" y="120" width="40" height="40" rx="5" fill="#3DB7D6"/>
                                            <rect x="220" y="120" width="40" height="40" rx="5" fill="#3DB7D6"/>
                                            <rect x="120" y="170" width="40" height="40" rx="5" fill="#3DB7D6"/>
                                            <rect x="170" y="170" width="40" height="40" rx="5" fill="#3DB7D6"/>
                                            <rect x="220" y="170" width="40" height="40" rx="5" fill="#3DB7D6"/>
                                            
                                            <!-- Door -->
                                            <rect x="165" y="210" width="50" height="40" rx="5" fill="#E63323"/>
                                            
                                            <!-- People -->
                                            <circle cx="190" cy="230" r="8" fill="#fff"/>
                                            <circle cx="210" cy="230" r="8" fill="#fff"/>
                                            
                                            <!-- Growth Arrows -->
                                            <path d="M50 150L100 150L100 100" stroke="#E63323" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M350 150L300 150L300 100" stroke="#E63323" stroke-width="2" stroke-linecap="round"/>
                                            
                                            <!-- Floating Elements -->
                                            <circle cx="60" cy="120" r="5" fill="#3DB7D6" fill-opacity="0.7">
                                                <animate attributeName="cy" values="120;110;120" dur="3s" repeatCount="indefinite"/>
                                            </circle>
                                            <circle cx="340" cy="120" r="5" fill="#E63323" fill-opacity="0.7">
                                                <animate attributeName="cy" values="120;130;120" dur="4s" repeatCount="indefinite"/>
                                            </circle>
                                            
                                            <!-- Gradient Definition -->
                                            <defs>
                                                <linearGradient id="buildingGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                    <stop offset="0%" stop-color="#f8f9fa"/>
                                                    <stop offset="100%" stop-color="#e9ecef"/>
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Decorative Elements -->
                                <div class="position-absolute top-0 start-0 m-4">
                                    <div class="bg-danger text-white rounded-circle p-2">
                                        <i class="bi bi-building fs-4"></i>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 end-0 m-4">
                                    <div class="bg-primary text-white rounded-circle p-2">
                                        <i class="bi bi-people fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-6 position-relative" style="background: linear-gradient(135deg, #333333 0%, #555555 100%);">
    <!-- Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-05">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#E63323" d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,128C672,96,768,64,864,74.7C960,85,1056,139,1152,138.7C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative">
        <div class="row g-5 text-center">
            <div class="col-md-3">
                <div class="counter-card">
                    <div class="counter-icon mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-20 text-danger d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-people-fill fs-2"></i>
                        </div>
                    </div>
                    <div class="counter-value display-4 fw-bold text-white mb-2" data-count="10000">0</div>
                    <div class="counter-label text-white opacity-75">Active Members</div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="counter-card">
                    <div class="counter-icon mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-20 text-primary d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-cash-coin fs-2"></i>
                        </div>
                    </div>
                    <div class="counter-value display-4 fw-bold text-white mb-2" data-count="500">0</div>
                    <div class="counter-label text-white opacity-75">Million+ Paid in Commissions</div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="counter-card">
                    <div class="counter-icon mb-3">
                        <div class="rounded-circle bg-success bg-opacity-20 text-success d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-trophy fs-2"></i>
                        </div>
                    </div>
                    <div class="counter-value display-4 fw-bold text-white mb-2" data-count="50">0</div>
                    <div class="counter-label text-white opacity-75">Rank Achievers</div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="counter-card">
                    <div class="counter-icon mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-20 text-warning d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-clock-fill fs-2"></i>
                        </div>
                    </div>
                    <div class="counter-value display-4 fw-bold text-white mb-2">24/7</div>
                    <div class="counter-label text-white opacity-75">Support Available</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Approach -->
<section class="py-6 bg-light">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Our <span class="text-danger">Approach</span></h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                We've developed a proven system that combines innovation with simplicity to create maximum value for our members
            </p>
        </div>
        
        <div class="row g-5">
            <!-- Step 1 -->
            <div class="col-md-4">
                <div class="approach-card text-center position-relative">
                    <div class="step-indicator mb-4">
                        <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center position-relative" style="width: 80px; height: 80px;">
                            <span class="h3 fw-bold mb-0">01</span>
                            <div class="position-absolute top-0 start-100 translate-middle">
                                <div class="bg-danger rounded-circle" style="width: 20px; height: 20px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="approach-icon mb-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-4 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-person-plus-fill fs-1"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">Strategic Registration</h4>
                    <p class="text-muted">
                        Sign up with a sponsor ID and select from our four optimized membership packages, 
                        each designed for different growth trajectories.
                    </p>
                    <div class="mt-4">
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i> Smart Placement
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="col-md-4">
                <div class="approach-card text-center position-relative">
                    <div class="step-indicator mb-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center position-relative" style="width: 80px; height: 80px;">
                            <span class="h3 fw-bold mb-0">02</span>
                            <div class="position-absolute top-0 start-100 translate-middle">
                                <div class="bg-primary rounded-circle" style="width: 20px; height: 20px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="approach-icon mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-4 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-diagram-3-fill fs-1"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">Network Expansion</h4>
                    <p class="text-muted">
                        Build your network using our efficient binary structure and earn multiple commissions 
                        from every level of your downline organization.
                    </p>
                    <div class="mt-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                            <i class="bi bi-lightning-charge me-1"></i> Exponential Growth
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="col-md-4">
                <div class="approach-card text-center position-relative">
                    <div class="step-indicator mb-4">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <span class="h3 fw-bold mb-0">03</span>
                        </div>
                    </div>
                    <div class="approach-icon mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-4 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-wallet2 fs-1"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">Wealth Generation</h4>
                    <p class="text-muted">
                        Withdraw earnings, shop premium products, and use VTU services - all from your 
                        multiple specialized wallets with instant processing.
                    </p>
                    <div class="mt-4">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                            <i class="bi bi-currency-exchange me-1"></i> Multiple Streams
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Our <span class="text-danger">Core Values</span></h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                The principles that guide our mission and define our commitment to members
            </p>
        </div>
        
        <div class="row g-5">
            @php
                $values = [
                    [
                        'icon' => 'bi-heart-fill',
                        'color' => 'danger',
                        'title' => 'Integrity',
                        'desc' => 'Transparent operations and honest communication in all our dealings.'
                    ],
                    [
                        'icon' => 'bi-people-fill',
                        'color' => 'primary',
                        'title' => 'Community',
                        'desc' => 'Building a supportive network where every member can thrive.'
                    ],
                    [
                        'icon' => 'bi-lightbulb-fill',
                        'color' => 'warning',
                        'title' => 'Innovation',
                        'desc' => 'Continuously improving our platform with cutting-edge technology.'
                    ],
                    [
                        'icon' => 'bi-trophy-fill',
                        'color' => 'success',
                        'title' => 'Excellence',
                        'desc' => 'Striving for the highest standards in service and support.'
                    ],
                ];
            @endphp
            
            @foreach($values as $value)
            <div class="col-md-6 col-lg-3">
                <div class="value-card text-center p-4 rounded-4 border-0 shadow-sm h-100">
                    <div class="value-icon mb-4">
                        <div class="rounded-circle bg-{{ $value['color'] }} bg-opacity-10 text-{{ $value['color'] }} p-4 d-inline-flex align-items-center justify-content-center">
                            <i class="bi {{ $value['icon'] }} fs-1"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">{{ $value['title'] }}</h4>
                    <p class="text-muted mb-0">{{ $value['desc'] }}</p>
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
                    Ready to Join Our Success Story?
                </h2>
                <p class="lead text-white opacity-75 mb-5 mx-auto" style="max-width: 700px;">
                    Become part of a community that's transforming lives and creating financial independence 
                    for thousands. Your journey starts here.
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-4 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-3">
                        <span>Start Your Journey</span>
                        <i class="bi bi-arrow-right-circle-fill" style="font-size: 1.2rem;"></i>
                    </a>
                    <a href="{{ route('packages') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-3 border-2">
                        View Packages
                    </a>
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
    
    .approach-card {
        padding: 2rem;
        border-radius: 1rem;
        background: #fff;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .approach-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    .value-card {
        transition: transform 0.3s ease;
    }
    
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .counter-card {
        padding: 2rem 1rem;
    }
    
    .opacity-05 {
        opacity: 0.05;
    }
    
    .opacity-10 {
        opacity: 0.1;
    }
    
    /* Animation for counters */
    .counter-value {
        transition: all 0.5s ease;
    }
    
    /* Step connector lines */
    @media (min-width: 768px) {
        .step-indicator:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 40px;
            left: 100%;
            width: calc(100% - 80px);
            height: 2px;
            background: linear-gradient(90deg, #E63323, #1FA3C4);
            z-index: 1;
        }
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
        
        .approach-card, .value-card {
            margin-bottom: 1.5rem;
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
        
        .counter-card {
            padding: 1.5rem 0;
        }
    }
</style>

<script>
    // Animated counter
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter-value[data-count]');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const suffix = counter.textContent.includes('Million') ? 'M+' : '';
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                if (suffix === 'M+') {
                    counter.textContent = Math.floor(current) + suffix;
                } else {
                    counter.textContent = Math.floor(current).toLocaleString();
                }
            }, 30);
        });
    });
</script>
@endsection