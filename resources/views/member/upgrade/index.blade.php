@extends('layouts.member')

@section('title', 'Upgrade Package - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-happylife-dark">Upgrade Package</h1>
            <p class="text-secondary">Move to a higher package and enjoy more benefits</p>
        </div>
        <a href="{{ route('member.profile.index') }}" class="btn btn-outline-happylife-teal">
            <i class="bi bi-arrow-left me-2"></i> Back to Profile
        </a>
    </div>

    <!-- Current Package Card -->
    <div class="card product-card p-4 mb-4 bg-gradient-light">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="fw-bold text-happylife-dark mb-2">Your Current Package</h5>
                <div class="d-flex align-items-center">
                    <div class="bg-happylife-red bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-box-seam fs-3 text-happylife-red"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-happylife-dark mb-0">{{ $currentPackage->name }}</h3>
                        <p class="text-secondary mb-0">
                            Price: ₦{{ number_format($currentPackage->price, 2) }} · 
                            PV: {{ $currentPackage->pv }} · 
                            Direct Bonus: ₦{{ number_format($currentPackage->direct_bonus_amount, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-happylife-teal fs-6 p-2">
                    <i class="bi bi-check-circle me-1"></i> Active
                </span>
            </div>
        </div>
    </div>

    <!-- Wallet Balance Alert -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="bi bi-wallet2 fs-4 me-3"></i>
        <div>
            <strong>Commission Wallet Balance:</strong>
            <span class="fw-bold fs-5 ms-2 text-happylife-red">₦{{ number_format($walletBalance, 2) }}</span>
            <span class="ms-3 text-secondary">This will be used to pay the upgrade difference.</span>
        </div>
    </div>

    <!-- Upgradeable Packages -->
    <h5 class="fw-bold text-happylife-dark mb-3">Available Upgrade Packages</h5>

    @if($upgradeablePackages->count() > 0)
        <div class="row g-4">
            @foreach($upgradeablePackages as $package)
                @php
                    $difference = $package->price - $currentPackage->price;
                    $canAfford = $walletBalance >= $difference;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card product-card h-100 p-4 {{ !$canAfford ? 'opacity-75' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold text-happylife-dark mb-0">{{ $package->name }}</h5>
                            <span class="badge bg-happylife-cyan">{{ $package->pv }} PV</span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary">Package Price</span>
                                <span class="fw-bold">₦{{ number_format($package->price, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary">Your Current Package</span>
                                <span class="fw-bold">- ₦{{ number_format($currentPackage->price, 2) }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">You Pay</span>
                                <span class="fw-bold text-happylife-red fs-5">₦{{ number_format($difference, 2) }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-secondary d-block">
                                <i class="bi bi-gift me-1"></i> Direct Bonus: ₦{{ number_format($package->direct_bonus_amount, 2) }}
                            </small>
                            <small class="text-secondary d-block">
                                <i class="bi bi-trophy me-1"></i> Daily Pairing Cap: ₦{{ number_format($package->pairing_cap, 2) }}
                            </small>
                        </div>

                        <form action="{{ route('member.upgrade.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <button type="submit" class="btn w-100 py-2 {{ $canAfford ? 'btn-happylife-red' : 'btn-secondary' }}" 
                                    {{ $canAfford ? '' : 'disabled' }}>
                                @if($canAfford)
                                    <i class="bi bi-arrow-up-circle me-2"></i> Upgrade Now
                                @else
                                    <i class="bi bi-exclamation-circle me-2"></i> Insufficient Balance
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card product-card p-5 text-center">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-3 text-secondary">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <h4 class="fw-bold text-happylife-dark">No Higher Packages Available</h4>
            <p class="text-secondary mb-0">You are currently on the highest package. Check back later for more options.</p>
        </div>
    @endif

    <!-- Upgrade Information -->
    <div class="row mt-5">
        <div class="col-md-4 mb-3">
            <div class="card product-card p-3 text-center">
                <i class="bi bi-calculator fs-1 text-happylife-teal mb-2"></i>
                <h6 class="fw-bold">Pay Only the Difference</h6>
                <p class="text-secondary small mb-0">You pay ₦{{ number_format($currentPackage->price, 2) }} less than the new package price.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card product-card p-3 text-center">
                <i class="bi bi-gift-fill fs-1 text-happylife-red mb-2"></i>
                <h6 class="fw-bold">New Product Included</h6>
                <p class="text-secondary small mb-0">You'll receive the product for your new package to claim.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card product-card p-3 text-center">
                <i class="bi bi-clock-history fs-1 text-happylife-cyan mb-2"></i>
                <h6 class="fw-bold">Instant Upgrade</h6>
                <p class="text-secondary small mb-0">Your package is upgraded immediately after payment.</p>
            </div>
        </div>
    </div>
</div>
@endsection