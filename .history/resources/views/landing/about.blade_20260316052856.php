@extends('layouts.app')

@section('title', 'About Us - ' . ($settings['site_name'] ?? 'Happylife Multipurpose Int\'l'))

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative py-5 py-lg-6">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1 class="display-4 fw-bold text-white mb-4">
                    {!! $settings['about_hero_title'] ?? 'About <span class="text-warning">Happylife</span>' !!}
                </h1>
                <p class="lead text-white opacity-75 mb-0">
                    {{ $settings['about_hero_subtitle'] ?? 'Revolutionizing Income Opportunities Through Hybrid MLM + E-Commerce + Reward + VTU Services' }}
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
                        {{ $settings['about_mission_badge'] ?? 'Our Mission' }}
                    </span>
                    <h2 class="display-5 fw-bold mb-4">
                        {!! $settings['about_mission_title'] ?? 'Empowering <span class="text-danger">Financial Independence</span>' !!}
                    </h2>
                    <p class="lead text-muted mb-4">
                        {{ $settings['about_mission_text'] ?? 'To create a platform where everyone can achieve financial freedom through multiple, sustainable income streams.' }}
                    </p>
                    <p class="text-muted mb-5">
                        {{ $settings['about_mission_description'] ?? 'Happylife Multipurpose Int\'l was founded with the mission to empower individuals by combining the best of MLM networking, e-commerce shopping, reward systems, and utility services in one comprehensive platform.' }}
                    </p>
                    
                    <!-- Key Features (can be made dynamic via JSON if needed, but for now keep static or use a simple array) -->
                    @php
                        $missionFeatures = $settings['about_mission_features'] ?? [
                            ['icon' => 'bi-cash-stack', 'title' => 'Multiple Income Streams', 'desc' => 'Earn from referrals, shopping, ranks, and VTU services'],
                            ['icon' => 'bi-graph-up-arrow', 'title' => 'Exponential Growth', 'desc' => 'Binary structure enables rapid network expansion'],
                            ['icon' => 'bi-shield-check', 'title' => 'Secure Platform', 'desc' => 'Bank-level security for all transactions and data'],
                            ['icon' => 'bi-award', 'title' => 'Proven Success', 'desc' => 'Thousands of members achieving financial goals'],
                        ];
                    @endphp
                    <div class="row g-4">
                        @foreach($missionFeatures as $feature)
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 me-3">
                                        <i class="bi {{ $feature['icon'] }} fs-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">{{ $feature['title'] }}</h5>
                                    <p class="text-muted small mb-0">{{ $feature['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <!-- About Illustration (same as before) -->
                <div class="position-relative">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="position-relative">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-5"></div>
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
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-05">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#E63323" d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,128C672,96,768,64,864,74.7C960,85,1056,139,1152,138.7C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative">
        <div class="row g-5 text-center">
            @php
                $stats = [
                    'stat1' => ['value' => '10K+', 'label' => 'Active Members'],
                    'stat2' => ['value' => '500M+', 'label' => 'Million+ Paid in Commissions'],
                    'stat3' => ['value' => '50', 'label' => 'Rank Achievers'],
                    'stat4' => ['value' => '24/7', 'label' => 'Support Available'],
                ];
            @endphp
            @foreach($stats as $key => $default)
            <div class="col-md-3">
                <div class="counter-card">
                    <div class="counter-icon mb-3">
                        @php
                            $icon = $key == 'stat1' ? 'bi-people-fill' : ($key == 'stat2' ? 'bi-cash-coin' : ($key == 'stat3' ? 'bi-trophy' : 'bi-clock-fill'));
                        @endphp
                        <div class="rounded-circle bg-{{ $key == 'stat1' ? 'danger' : ($key == 'stat2' ? 'primary' : ($key == 'stat3' ? 'success' : 'warning')) }} bg-opacity-20 text-{{ $key == 'stat1' ? 'danger' : ($key == 'stat2' ? 'primary' : ($key == 'stat3' ? 'success' : 'warning')) }} d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi {{ $icon }} fs-2"></i>
                        </div>
                    </div>
                    <div class="counter-value display-4 fw-bold text-white mb-2" data-count="{{ $settings[$key.'_value'] ?? $default['value'] }}">
                        {{ $settings[$key.'_value'] ?? $default['value'] }}
                    </div>
                    <div class="counter-label text-white opacity-75">{{ $settings[$key.'_label'] ?? $default['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Our Approach -->
<section class="py-6 bg-light">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Our <span class="text-danger">Approach</span></h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                {{ $settings['about_approach_subtitle'] ?? 'We\'ve developed a proven system that combines innovation with simplicity to create maximum value for our members' }}
            </p>
        </div>
        
        @php
            $approachSteps = $settings['about_approach_steps'] ?? [
                [
                    'title' => 'Strategic Registration',
                    'desc' => 'Sign up with a sponsor ID and select from our four optimized membership packages, each designed for different growth trajectories.',
                    'badge' => 'Smart Placement',
                    'color' => 'danger'
                ],
                [
                    'title' => 'Network Expansion',
                    'desc' => 'Build your network using our efficient binary structure and earn multiple commissions from every level of your downline organization.',
                    'badge' => 'Exponential Growth',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Wealth Generation',
                    'desc' => 'Withdraw earnings, shop premium products, and use VTU services - all from your multiple specialized wallets with instant processing.',
                    'badge' => 'Multiple Streams',
                    'color' => 'success'
                ],
            ];
        @endphp
        
        <div class="row g-5">
            @foreach($approachSteps as $index => $step)
            <div class="col-md-4">
                <div class="approach-card text-center position-relative">
                    <div class="step-indicator mb-4">
                        <div class="rounded-circle bg-{{ $step['color'] }} text-white d-inline-flex align-items-center justify-content-center position-relative" style="width: 80px; height: 80px;">
                            <span class="h3 fw-bold mb-0">{{ str_pad($index+1, 2, '0', STR_PAD_LEFT) }}</span>
                            @if(!$loop->last)
                            <div class="position-absolute top-0 start-100 translate-middle">
                                <div class="bg-{{ $step['color'] }} rounded-circle" style="width: 20px; height: 20px;"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="approach-icon mb-4">
                        <div class="rounded-circle bg-{{ $step['color'] }} bg-opacity-10 text-{{ $step['color'] }} p-4 d-inline-flex align-items-center justify-content-center">
                            @if($index == 0)
                                <i class="bi bi-person-plus-fill fs-1"></i>
                            @elseif($index == 1)
                                <i class="bi bi-diagram-3-fill fs-1"></i>
                            @else
                                <i class="bi bi-wallet2 fs-1"></i>
                            @endif
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">{{ $step['title'] }}</h4>
                    <p class="text-muted">{{ $step['desc'] }}</p>
                    <div class="mt-4">
                        <span class="badge bg-{{ $step['color'] }} bg-opacity-10 text-{{ $step['color'] }} px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i> {{ $step['badge'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Core Values (static, but could be made dynamic if needed) -->
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
                    ['icon' => 'bi-heart-fill', 'color' => 'danger', 'title' => 'Integrity', 'desc' => 'Transparent operations and honest communication in all our dealings.'],
                    ['icon' => 'bi-people-fill', 'color' => 'primary', 'title' => 'Community', 'desc' => 'Building a supportive network where every member can thrive.'],
                    ['icon' => 'bi-lightbulb-fill', 'color' => 'warning', 'title' => 'Innovation', 'desc' => 'Continuously improving our platform with cutting-edge technology.'],
                    ['icon' => 'bi-trophy-fill', 'color' => 'success', 'title' => 'Excellence', 'desc' => 'Striving for the highest standards in service and support.'],
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
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,128C672,96,768,64,864,74.7C960,85,1056,139,1152,138.7C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="display-4 fw-bold text-white mb-4">
                    {{ $settings['cta_title'] ?? 'Ready to Join Our Success Story?' }}
                </h2>
                <p class="lead text-white opacity-75 mb-5 mx-auto" style="max-width: 700px;">
                    {{ $settings['cta_subtitle'] ?? 'Become part of a community that\'s transforming lives and creating financial independence for thousands. Your journey starts here.' }}
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-4 justify-content-center">
                    <a href="{{ $settings['cta_button_link'] ?? route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-3">
                        <span>{{ $settings['cta_button_text'] ?? 'Start Your Journey' }}</span>
                        <i class="bi bi-arrow-right-circle-fill" style="font-size: 1.2rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* (Keep all existing styles exactly as before) */
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
    
    .counter-value {
        transition: all 0.5s ease;
    }
    
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
            const targetText = counter.getAttribute('data-count');
            let target;
            let suffix = '';
            if (targetText.includes('K+')) {
                target = parseInt(targetText);
                suffix = 'K+';
            } else if (targetText.includes('M+')) {
                target = parseInt(targetText);
                suffix = 'M+';
            } else if (targetText === '24/7') {
                // don't animate
                return;
            } else {
                target = parseInt(targetText);
            }
            
            if (isNaN(target)) return;
            
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current) + suffix;
            }, 30);
        });
    });
</script>
@endsection