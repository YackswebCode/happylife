@extends('layouts.app')

@section('title', 'Membership Packages - Happylife Multipurpose Int\'l')

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
                <h1 class="display-4 fw-bold text-white mb-4">Choose Your <span class="text-warning">Path</span> to Success</h1>
                <p class="lead text-white opacity-75 mb-0">
                    Select the perfect package that aligns with your financial ambitions and start building your empire today
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Packages Visualization -->
<section class="py-6 bg-light">
    <div class="container">
        <div class="text-center mb-6">
            <span class="badge bg-danger bg-opacity-10 text-danger px-4 py-2 rounded-pill fw-semibold mb-3">
                Investment Levels
            </span>
            <h2 class="display-5 fw-bold mb-4">Strategic <span class="text-danger">Membership</span> Tiers</h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Each package is designed to provide maximum value and accelerate your journey to financial independence
            </p>
        </div>

        <!-- Interactive Package Visualization -->
        <div class="package-visualization mb-6">
            <div class="row justify-content-center align-items-end g-4">
                @foreach($packages as $index => $package)
                <div class="col-md-6 col-lg-3">
                    <div class="position-relative">
                        <!-- Package Height Visualization -->
                        <div class="package-visual d-flex flex-column align-items-center">
                            @php
                                $heights = [60, 80, 120, 180];
                                $height = $heights[$index] ?? 100;
                            @endphp
                            <div class="package-bar rounded-top-3" style="height: {{ $height }}px; background: linear-gradient(180deg, {{ $index == 1 ? '#E63323' : ($index == 2 ? '#1FA3C4' : ($index == 3 ? '#FFD700' : '#3DB7D6')) }} 0%, rgba({{ $index == 1 ? '230,51,35' : ($index == 2 ? '31,163,196' : ($index == 3 ? '255,215,0' : '61,183,214')) }}, 0.8) 100%);">
                                <div class="package-value text-white fw-bold text-center" style="padding-top: {{ $height/2 }}px;">
                                    {{ $package->pv }} PV
                                </div>
                            </div>
                            <div class="package-label mt-3">
                                <h4 class="fw-bold mb-2">{{ $package->name }}</h4>
                                <div class="text-muted small">Starts from</div>
                                <div class="h5 fw-bold text-danger">Product Worth ₦{{ number_format($package->price, 2) }}</div>
                            </div>
                        </div>
                        
                        <!-- Popular Badge -->
                        @if($index == 1)
                        <div class="position-absolute top-0 start-50 translate-middle mt-n3">
                            <span class="badge bg-danger px-4 py-2 rounded-pill fw-bold">
                                <i class="bi bi-star-fill me-1"></i> Most Popular
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
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

<!-- Package Comparison -->
<section class="py-6 bg-light">
    <div class="container">
        <div class="text-center mb-6">
            <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill fw-semibold mb-3">
                Feature Comparison
            </span>
            <h2 class="display-5 fw-bold mb-4">Detailed Package <span class="text-danger">Comparison</span></h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Compare all features and benefits across our four membership tiers to make an informed decision
            </p>
        </div>
        
        <div class="comparison-table">
            <div class="table-responsive rounded-4 shadow-lg">
                <table class="table table-hover mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="p-4 text-start align-middle" style="width: 30%;">Features & Benefits</th>
                            @foreach($packages as $package)
                            <th class="p-4 text-center align-middle" style="width: 17.5%;">
                                <div class="package-comparison-header">
                                    <div class="fw-bold mb-2">{{ $package->name }}</div>
                                    <div class="small">₦{{ number_format($package->price, 2) }} Product</div>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Product Value -->
                        <tr class="bg-white">
                            <td class="p-4 fw-bold">Product Value</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                <div class="h5 fw-bold text-danger">₦{{ number_format($package->price, 2) }}</div>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- PV Value -->
                        <tr class="bg-light">
                            <td class="p-4 fw-bold">PV Value</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                    {{ $package->pv }} PV
                                </span>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Direct Referral Bonus -->
                        <tr class="bg-white">
                            <td class="p-4 fw-bold">Direct Referral Bonus</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                @php
                                    $bonuses = [
                                        'Sapphire' => 1000,
                                        'Ohekem' => 1500,
                                        'Lifestyle' => 10250,
                                        'Business Guru' => 56250
                                    ];
                                @endphp
                                <div class="h5 fw-bold text-primary">₦{{ number_format($bonuses[$package->name] ?? 0, 2) }}</div>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Pairing Bonus -->
                        <tr class="bg-light">
                            <td class="p-4 fw-bold">Pairing Bonus</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Shopping Bonus -->
                        <tr class="bg-white">
                            <td class="p-4 fw-bold">Shopping Bonus (₦250 per repurchase)</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Rank Achievement -->
                        <tr class="bg-light">
                            <td class="p-4 fw-bold">Rank Achievement</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- VTU Services -->
                        <tr class="bg-white">
                            <td class="p-4 fw-bold">VTU Services</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Withdrawal Access -->
                        <tr class="bg-light">
                            <td class="p-4 fw-bold">Withdrawal Access</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Priority Support -->
                        <tr class="bg-white">
                            <td class="p-4 fw-bold">Priority Support</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                @if(in_array($package->name, ['Lifestyle', 'Business Guru']))
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                @else
                                <i class="bi bi-dash-circle text-muted fs-4"></i>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Training Sessions -->
                        <tr class="bg-light">
                            <td class="p-4 fw-bold">Advanced Training</td>
                            @foreach($packages as $package)
                            <td class="p-4 text-center">
                                @if($package->name == 'Business Guru')
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                @else
                                <i class="bi bi-dash-circle text-muted fs-4"></i>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-center gap-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="text-muted small">Included</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-dash-circle text-muted me-2"></i>
                        <span class="text-muted small">Not Included</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Earnings Potential -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <span class="badge bg-warning bg-opacity-10 text-warning px-4 py-2 rounded-pill fw-semibold mb-3">
                Earnings Potential
            </span>
            <h2 class="display-5 fw-bold mb-4">Your <span class="text-danger">Earning</span> Potential</h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                See how each package accelerates your journey to financial freedom through multiple income streams
            </p>
        </div>
        
        <div class="row g-5">
            @foreach($packages as $index => $package)
            <div class="col-lg-3 col-md-6">
                <div class="earnings-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-3">{{ $package->name }}</h4>
                            <div class="earnings-icon mb-3">
                                @switch($index)
                                    @case(0)
                                        <i class="bi bi-graph-up text-primary fs-1"></i>
                                        @break
                                    @case(1)
                                        <i class="bi bi-rocket-takeoff text-danger fs-1"></i>
                                        @break
                                    @case(2)
                                        <i class="bi bi-lightning-charge text-success fs-1"></i>
                                        @break
                                    @case(3)
                                        <i class="bi bi-bar-chart-line text-warning fs-1"></i>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Direct Referral</span>
                                <span class="fw-bold text-success">
                                    @php
                                        $bonuses = [
                                            'Sapphire' => 1000,
                                            'Ohekem' => 1500,
                                            'Lifestyle' => 10250,
                                            'Business Guru' => 56250
                                        ];
                                    @endphp
                                    ₦{{ number_format($bonuses[$package->name] ?? 0, 2) }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Pairing Bonus</span>
                                <span class="fw-bold text-success">₦1,500/pair</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Shopping Bonus</span>
                                <span class="fw-bold text-success">₦250/sale</span>
                            </li>
                        </ul>
                        
                        <div class="text-center">
                            <div class="potential-earnings mb-3">
                                <div class="text-muted small mb-2">Monthly Potential</div>
                                <div class="h4 fw-bold text-danger">
                                    @php
                                        $potentials = [
                                            'Sapphire' => '₦50,000+',
                                            'Ohekem' => '₦150,000+',
                                            'Lifestyle' => '₦500,000+',
                                            'Business Guru' => '₦2,000,000+'
                                        ];
                                    @endphp
                                    {{ $potentials[$package->name] ?? 'Varies' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-6 bg-light">
    <div class="container">
        <div class="text-center mb-6">
            <span class="badge bg-info bg-opacity-10 text-info px-4 py-2 rounded-pill fw-semibold mb-3">
                Common Questions
            </span>
            <h2 class="display-5 fw-bold mb-4">Package <span class="text-danger">FAQs</span></h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Get answers to frequently asked questions about our membership packages
            </p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="packageFaqAccordion">
                    <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                        <h2 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button collapsed rounded-4 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                                <span class="fw-bold me-3">Can I upgrade my package later?</span>
                            </button>
                        </h2>
                        <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faqHeading1" data-bs-parent="#packageFaqAccordion">
                            <div class="accordion-body pt-0">
                                <div class="answer-content p-4">
                                    <p class="mb-0">Yes, you can upgrade your package at any time from your member dashboard. The upgrade bonus is shared between upline and downline, and you only pay the difference between your current package and the new package.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                        <h2 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed rounded-4 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                <span class="fw-bold me-3">What happens to unused PV?</span>
                            </button>
                        </h2>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#packageFaqAccordion">
                            <div class="accordion-body pt-0">
                                <div class="answer-content p-4">
                                    <p class="mb-0">Unused PV rolls over to the next day, so you never lose your points. This ensures that you get maximum value from your PV accumulation and can achieve pairing bonuses more consistently.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                        <h2 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed rounded-4 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                <span class="fw-bold me-3">When do I receive my product?</span>
                            </button>
                        </h2>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#packageFaqAccordion">
                            <div class="accordion-body pt-0">
                                <div class="answer-content p-4">
                                    <p class="mb-0">You can claim your product from your dashboard by selecting your pickup state and center after registration. Products are typically available for pickup within 3-5 business days after successful registration.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                        <h2 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed rounded-4 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                <span class="fw-bold me-3">Can I change my selected product?</span>
                            </button>
                        </h2>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#packageFaqAccordion">
                            <div class="accordion-body pt-0">
                                <div class="answer-content p-4">
                                    <p class="mb-0">Product selection can be changed within 24 hours of registration. After that, changes may be subject to availability and administrative fees. Contact support for assistance with product changes.</p>
                                </div>
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
                    Ready to Start Your Journey?
                </h2>
                <p class="lead text-white opacity-75 mb-5 mx-auto" style="max-width: 700px;">
                    Choose the package that fits your goals and join thousands of members building 
                    sustainable wealth through our proven system.
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-4 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-3">
                        <i class="bi bi-rocket-takeoff" style="font-size: 1.2rem;"></i>
                        <span>Get Started Now</span>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-3 border-2">
                        Need Help Choosing?
                    </a>
                </div>
                
                <div class="mt-5">
                    <div class="row g-4 justify-content-center">
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-shield-check me-2"></i>
                                <span>30-Day Satisfaction Guarantee</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                <span>Flexible Upgrade Options</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-white">
                                <i class="bi bi-headset me-2"></i>
                                <span>Dedicated Support</span>
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
    
    .package-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .package-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    }
    
    .premium-package {
        border: 3px solid #E63323 !important;
        transform: scale(1.05);
    }
    
    .package-bar {
        width: 100%;
        max-width: 120px;
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .package-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.2) 0%, transparent 100%);
        pointer-events: none;
    }
    
    .package-value {
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .package-visual:hover .package-bar {
        transform: scale(1.05);
    }
    
    .comparison-table th, .comparison-table td {
        border: 1px solid #e9ecef;
    }
    
    .comparison-table thead th {
        border-bottom: 2px solid #dee2e6;
    }
    
    .earnings-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .earnings-card:hover {
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
        
        .premium-package {
            transform: scale(1);
            margin-top: 1rem;
        }
        
        .package-bar {
            max-width: 80px;
        }
        
        .package-visual {
            margin-bottom: 2rem;
        }
        
        .comparison-table {
            font-size: 0.9rem;
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
        
        .package-card {
            margin-bottom: 1.5rem;
        }
        
        .earnings-card {
            margin-bottom: 1.5rem;
        }
    }
</style>

<script>
    // Package bar hover animation
    document.addEventListener('DOMContentLoaded', function() {
        const packageBars = document.querySelectorAll('.package-bar');
        
        packageBars.forEach(bar => {
            bar.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            bar.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Package card interaction
        const packageCards = document.querySelectorAll('.package-card');
        
        packageCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                const header = this.querySelector('.package-header');
                if (header) {
                    header.style.transition = 'all 0.3s ease';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                const header = this.querySelector('.package-header');
                if (header) {
                    header.style.transition = 'all 0.3s ease';
                }
            });
        });
    });
</script>
@endsection