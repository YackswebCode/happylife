@extends('layouts.app')

@section('title', 'Happylife Multipurpose Int\'l - Hybrid MLM Platform')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
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
                        {!! $settings['hero_badge'] !!}
                    </span>
                    <h1 class="display-4 fw-bold text-white mb-4">
                        {!! $settings['hero_title'] !!}
                    </h1>
                    <p class="lead text-white opacity-75 mb-5">
                        {{ $settings['hero_subtitle'] }}
                    </p>
                </div>
                
                <div class="d-flex flex-column flex-sm-row gap-3 mb-5">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <span>Start Earning Today</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="row g-4">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h2 fw-bold text-white mb-1">{{ $settings['stat1_value'] }}</div>
                            <div class="text-white opacity-75 small">{{ $settings['stat1_label'] }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h2 fw-bold text-white mb-1">{{ $settings['stat2_value'] }}</div>
                            <div class="text-white opacity-75 small">{{ $settings['stat2_label'] }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="h2 fw-bold text-white mb-1">{{ $settings['stat3_value'] }}</div>
                            <div class="text-white opacity-75 small">{{ $settings['stat3_label'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <!-- Hero SVG Illustration (unchanged) -->
                <div class="position-relative">
                    <div class="position-relative">
                        <svg class="w-100" viewBox="0 0 600 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="300" cy="200" r="180" fill="url(#gradient1)" fill-opacity="0.2"/>
                            <circle cx="300" cy="200" r="8" fill="#E63323" stroke="#fff" stroke-width="3"/>
                            <circle cx="150" cy="150" r="6" fill="#1FA3C4"/>
                            <circle cx="150" cy="250" r="6" fill="#1FA3C4"/>
                            <circle cx="300" cy="100" r="6" fill="#1FA3C4"/>
                            <circle cx="300" cy="300" r="6" fill="#1FA3C4"/>
                            <circle cx="450" cy="150" r="6" fill="#1FA3C4"/>
                            <circle cx="450" cy="250" r="6" fill="#1FA3C4"/>
                            <line x1="300" y1="200" x2="150" y2="150" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="150" y2="250" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="300" y2="100" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="300" y2="300" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="450" y2="150" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <line x1="300" y1="200" x2="450" y2="250" stroke="#1FA3C4" stroke-width="2" stroke-opacity="0.5"/>
                            <circle cx="100" cy="100" r="4" fill="#E63323" fill-opacity="0.7">
                                <animate attributeName="cy" values="100;90;100" dur="3s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="500" cy="100" r="4" fill="#3DB7D6" fill-opacity="0.7">
                                <animate attributeName="cx" values="500;510;500" dur="4s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="100" cy="300" r="4" fill="#3DB7D6" fill-opacity="0.7">
                                <animate attributeName="cx" values="100;90;100" dur="3.5s" repeatCount="indefinite"/>
                            </circle>
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

<!-- Features Section (Dynamic) -->
<section class="py-6 position-relative bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Multiple <span class="text-danger">Income Streams</span>, One Platform</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Diversify your earnings with our comprehensive ecosystem designed for maximum profitability
            </p>
        </div>
        
        <div class="row g-5">
            @forelse($settings['landing_features'] as $feature)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-lg rounded-4 h-100 hover-lift">
                        <div class="card-body p-4 text-center">
                            <div class="icon-wrapper mb-4 mx-auto">
                                <i class="bi {{ $feature['icon'] ?? 'bi-stars' }}" style="font-size: 3rem; color: #E63323;"></i>
                            </div>
                            <h4 class="fw-bold mb-3">{{ $feature['title'] ?? '' }}</h4>
                            <p class="text-muted mb-0">{{ $feature['description'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No features configured yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Products Section (unchanged, uses $products) -->
<section class="py-6 bg-light position-relative">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-03">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#E63323" d="M0,160L48,176C96,192,192,224,288,208C384,192,480,128,576,112C672,96,768,128,864,144C960,160,1056,160,1152,144C1248,128,1344,96,1392,80L1440,64L1440,320L1392,320C1344,320C1248,320C1152,320C1056,320C960,320C864,320C768,320C672,320C576,320C480,320C384,320C288,320C192,320C96,320C48,320L0,320Z"></path>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Our <span class="text-danger">Products</span></h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">Discover our curated collection of high-quality products.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @forelse($products as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-lg rounded-4 h-100 product-card">
                        <div class="position-relative overflow-hidden rounded-top-4" style="height: 200px;">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid w-100 h-100 object-fit-cover" style="transition: transform 0.3s ease;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted w-100 h-100">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-0 hover-overlay"></div>
                        </div>
                        <div class="card-body p-4 text-center">
                            <h3 class="h5 fw-bold mb-2 text-truncate">{{ $product->name }}</h3>
                            <p class="text-muted small mb-3" style="min-height: 3em;">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
                            <div class="fw-bold text-danger fs-5">₦{{ number_format($product->display_price, 2) }}</div>
                            @if($product->category)<span class="badge bg-teal-blue mt-3">{{ $product->category }}</span>@endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center"><p class="text-muted">No products available at the moment.</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- How It Works Section (Dynamic) -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">How It <span class="text-danger">Works</span></h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">Follow these simple steps to start earning with Happylife</p>
        </div>
        <div class="row g-5">
            @forelse($settings['landing_steps'] as $index => $step)
            <div class="col-md-6 col-lg-3">
                <div class="step-card text-center position-relative">
                    <div class="step-number mb-4">
                        <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="h4 fw-bold mb-0">{{ $index + 1 }}</span>
                        </div>
                    </div>
                    <div class="step-icon mb-4">
                        <i class="bi {{ $step['icon'] ?? 'bi-arrow-right-circle' }}" style="font-size: 2.5rem; color: #E63323;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">{{ $step['title'] ?? '' }}</h4>
                    <p class="text-muted">{{ $step['description'] ?? '' }}</p>
                </div>
            </div>
            @empty
                <div class="col-12 text-center"><p class="text-muted">Steps not configured yet.</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- Team Section (Dynamic) -->
<section class="py-6 bg-light position-relative">
    <div class="container position-relative">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Meet Our <span class="text-danger">Team</span></h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">The people behind the vision, growth, and daily success of Happylife Multipurpose Int'l.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @forelse($settings['team_members'] as $member)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-lg rounded-4 h-100 team-card overflow-hidden">
                        <div class="position-relative" style="height: 280px;">
                            <img src="{{ asset('storage/' . ($member['image'] ?? 'images/team/default.jpg')) }}" alt="{{ $member['name'] ?? 'Team Member' }}" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute top-0 start-0 w-100 h-100 team-overlay"></div>
                        </div>
                        <div class="card-body p-4 text-center">
                            <h4 class="fw-bold mb-1">{{ $member['name'] ?? '' }}</h4>
                            <p class="text-danger fw-semibold mb-3">{{ $member['role'] ?? '' }}</p>
                            <p class="text-muted small mb-0">{{ $member['bio'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center"><p class="text-muted">Team information coming soon.</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section (Dynamic) -->
<section class="py-6 position-relative overflow-hidden" style="background: linear-gradient(135deg, #E63323 0%, #d6281a 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,128C672,96,768,64,864,74.7C960,85,1056,139,1152,138.7C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="display-4 fw-bold text-white mb-4">{{ $settings['cta_title'] }}</h2>
                <p class="lead text-white opacity-75 mb-5 mx-auto" style="max-width: 700px;">{{ $settings['cta_subtitle'] }}</p>
                <div class="d-flex flex-column flex-sm-row gap-4 justify-content-center">
                    <a href="{{ $settings['cta_button_link'] }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-3">
                        <span>{{ $settings['cta_button_text'] }}</span>
                        <i class="bi bi-arrow-right-circle-fill" style="font-size: 1.2rem;"></i>
                    </a>
                    <a href="{{ $settings['cta_secondary_link'] }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-3 border-2">
                        {{ $settings['cta_secondary_text'] }}
                    </a>
                </div>
                @if(!empty($settings['cta_features']) && is_array($settings['cta_features']))
                <div class="mt-5">
                    <div class="row g-4 justify-content-center">
                        @foreach($settings['cta_features'] as $feature)
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi {{ $feature['icon'] ?? 'bi-check-circle' }} me-2"></i>
                                <span>{{ $feature['text'] ?? '' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    /* Custom Styles (keep all your existing CSS) */
    .py-6 { padding-top: 4rem !important; padding-bottom: 4rem !important; }
    .py-lg-6 { padding-top: 5rem !important; padding-bottom: 5rem !important; }
    .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-lift:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; }
    .step-card { padding: 2rem; border-radius: 1rem; background: #fff; transition: all 0.3s ease; }
    .step-card:hover { background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%); }
    .opacity-03 { opacity: 0.03; }
    .opacity-10 { opacity: 0.1; }
    .product-card { overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 30px rgba(0,0,0,0.15) !important; }
    .product-card:hover .object-fit-cover { transform: scale(1.05); }
    .object-fit-cover { object-fit: cover; transition: transform 0.3s ease; }
    .hover-overlay { transition: opacity 0.3s ease; }
    .product-card:hover .hover-overlay { opacity: 0.1; }
    .bg-teal-blue { background-color: #1FA3C4; }
    .team-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .team-card:hover { transform: translateY(-8px); box-shadow: 0 20px 30px rgba(0,0,0,0.12) !important; }
    .team-overlay { background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.30) 100%); }
    @media (max-width: 768px) {
        .display-4 { font-size: 2.5rem; }
        .display-5 { font-size: 2rem; }
        .py-6 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
        .step-card { padding: 1.5rem; margin-bottom: 1rem; }
    }
    @media (max-width: 576px) {
        .display-4 { font-size: 2rem; }
        .display-5 { font-size: 1.75rem; }
        .btn-lg { padding: 0.75rem 1.5rem; font-size: 1rem; }
        .card { margin-bottom: 1rem; }
    }
</style>
@endsection