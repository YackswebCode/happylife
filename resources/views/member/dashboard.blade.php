@extends('layouts.member')

@section('title', 'Dashboard - Happylife Multipurpose Int\'l')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-dark-gray">Welcome back, {{ $user->name }}!</h1>
                <p class="text-muted mb-0">Here's what's happening with your business today.</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-gradient-teal text-white badge-custom me-2">
                    <i class="bi bi-award me-1"></i> {{ $user->package->name ?? 'Member' }}
                </span>
                <span class="badge bg-light text-dark-gray badge-custom">
                    <i class="bi bi-calendar-check me-1"></i>
                    {{ $user->activated_at ? $user->activated_at->format('M d, Y') : 'Not Activated' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Referral Code Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-2 text-dark-gray"><i class="bi bi-share-fill text-red me-2"></i>Your Referral Code</h5>
                        <p class="text-muted mb-3">Share this code to refer new members and earn commissions</p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="input-group">
                                <input type="text" class="form-control border-end-0 bg-light" id="referralCode" 
                                       value="{{ $user->referral_code ?? 'N/A' }}" readonly>
                               <button class="btn btn-red" type="button" onclick="copyReferralCode(this)">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label mb-2">TOTAL PV</h6>
                        <h3 class="stat-value mb-0 text-red">{{ number_format($stats['total_pv'], 1) }}</h3>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                                Current PV: {{ number_format($stats['current_pv'], 1) }}
                            </small>
                        </div>
                    </div>
                    <div class="stat-icon text-red opacity-75">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label mb-2">COMMISSION BALANCE</h6>
                        <h3 class="stat-value mb-0 text-teal-blue">₦{{ number_format($stats['commission_wallet_balance'], 2) }}</h3>
                        <div class="mt-2">
                           <a href="{{ route('member.withdraw.index') }}">
                             <button class="btn btn-sm btn-red">
                                <i class="bi bi-cash-coin me-1"></i> Withdraw
                            </button>
                           </a>
                        </div>
                    </div>
                    <div class="stat-icon text-teal-blue opacity-75">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label mb-2">NETWORK TEAM</h6>
                        <h3 class="stat-value mb-0 text-dark-gray">{{ $stats['left_count'] + $stats['right_count'] }}</h3>
                        <div class="mt-2">
                            <small class="text-muted d-flex align-items-center">
                                <span class="badge bg-success me-2">L: {{ $stats['left_count'] }}</span>
                                <span class="badge bg-danger">R: {{ $stats['right_count'] }}</span>
                            </small>
                        </div>
                    </div>
                    <div class="stat-icon text-dark-gray opacity-75">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label mb-2">WALLET BALANCE</h6>
                        <div class="mt-2">
                             <span class="fw-bold text-teal-blue">₦{{ number_format($stats['shopping_balance'], 2) }}</span>
                        </div>
                    </div>
                    <div class="stat-icon text-success opacity-75">
                           <i class="bi bi-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="col-xl-12 col-md-12 mb-4">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="stat-label mb-2">ACCOUNT STATUS</h6>
                        <h3 class="stat-value mb-0 text-success">{{ ucfirst($user->status) }}</h3>
                        <div class="mt-2">
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ ucfirst($user->payment_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon text-success opacity-75">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header-custom rounded-top">
                <h5 class="mb-0 text-white"><i class="bi bi-lightning-fill me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body p-4">
          <div class="row g-3">
    <!-- Genealogy Tree -->
    <div class="col-lg-4 col-md-6">
        <a href="{{ route('member.genealogy.index') }}" class="card action-card border-0 text-decoration-none">
            <div class="card-body text-center p-4">
                   <div class="icon-wrapper bg-soft-cyan rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-diagram-3-fill fs-4 text-white"></i>
                </div>
                <h6 class="mb-2 text-dark-gray">Genealogy Tree</h6>
                <small class="text-muted">View your network structure</small>
            </div>
        </a>
    </div>
    
    <!-- Shopping Mall -->
    <div class="col-lg-4 col-md-6">
        <a href="{{ route('member.shopping.index') }}" class="card action-card border-0 text-decoration-none">
            <div class="card-body text-center p-4">
                <div class="icon-wrapper bg-gradient-red rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-cart-fill fs-4 text-white"></i>
                </div>
                <h6 class="mb-2 text-dark-gray">Shopping Mall</h6>
                <small class="text-muted">Shop products & repurchase</small>
            </div>
        </a>
    </div>
    
    <!-- VTU Services -->
    <div class="col-lg-4 col-md-6">
        <a href="{{ route('member.vtu.index') }}" class="card action-card border-0 text-decoration-none">
            <div class="card-body text-center p-4">
                <div class="icon-wrapper bg-teal-blue rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-phone-fill fs-4 text-white"></i>
                </div>
                <h6 class="mb-2 text-dark-gray">VTU Services</h6>
                <small class="text-muted">Buy airtime, data, bills</small>
            </div>
        </a>
    </div>
</div>
            </div>
        </div>
    </div>
</div>


<!-- Network PV Statistics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-bar-chart-fill text-red me-2"></i>Network PV Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light-red h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-arrow-left-circle-fill fs-1 text-red"></i>
                                </div>
                                <h2 class="text-red mb-2">{{ number_format($user->left_pv, 1) }}</h2>
                                <p class="text-muted mb-0">Left Team PV</p>
                                <small class="text-muted">Members: {{ $stats['left_count'] }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light-teal h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-arrow-right-circle-fill fs-1 text-teal-blue"></i>
                                </div>
                                <h2 class="text-teal-blue mb-2">{{ number_format($user->right_pv, 1) }}</h2>
                                <p class="text-muted mb-0">Right Team PV</p>
                                <small class="text-muted">Members: {{ $stats['right_count'] }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light-purple h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-plus-circle-fill fs-1 text-purple"></i>
                                </div>
                                <h2 class="text-purple mb-2">{{ number_format($user->left_pv + $user->right_pv, 1) }}</h2>
                                <p class="text-muted mb-0">Total Network PV</p>
                                <small class="text-muted">Members: {{ $stats['left_count'] + $stats['right_count'] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bonus Breakdown -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-trophy-fill text-warning me-2"></i>Bonus Breakdown</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Direct Bonus</h6>
                                        <small class="text-muted">From direct referrals</small>
                                    </div>
                                    <span class="badge bg-success">₦{{ number_format($user->direct_bonus_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Level 2 Bonus</h6>
                                        <small class="text-muted">Indirect level 2</small>
                                    </div>
                                    <span class="badge bg-info">₦{{ number_format($user->indirect_level_2_bonus_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Level 3 Bonus</h6>
                                        <small class="text-muted">Indirect level 3</small>
                                    </div>
                                    <span class="badge bg-primary">₦{{ number_format($user->indirect_level_3_bonus_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Matching Bonus</h6>
                                        <small class="text-muted">PV matching bonus</small>
                                    </div>
                                    <span class="badge bg-warning">₦{{ number_format($user->matching_pv_bonus_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Rank Bonus</h6>
                                        <small class="text-muted">Rank achievement</small>
                                    </div>
                                    <span class="badge bg-purple">₦{{ number_format($user->rank_bonus_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Repurchase Bonus</h6>
                                        <small class="text-muted">From repurchases</small>
                                    </div>
                                    <span class="badge bg-teal">₦{{ number_format($user->repurchase_bonus_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Lifestyle Bonus</h6>
                                        <small class="text-muted">Lifestyle achievement</small>
                                    </div>
                                    <span class="badge bg-pink">₦{{ number_format($user->lifestyle_bonus_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Package & Rank Information -->
<div class="row">
    <!-- Package Details -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-box-seam text-red me-2"></i>Package Details</h5>
            </div>
            <div class="card-body">
                @if($user->package)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light-red border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="package-icon bg-red rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-box-seam text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-red">{{ $user->package->name }}</h5>
                                        <small class="text-muted">Active Package</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Price</small>
                                        <strong>₦{{ number_format($user->package->price, 2) }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">PV Value</small>
                                        <strong>{{ $user->package->pv }} PV</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td><small class="text-muted">Product Entitlement</small></td>
                                        <td><strong>{{ $user->package->product_entitlement }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><small class="text-muted">Direct Bonus</small></td>
                                        <td><strong>₦{{ number_format($user->package->direct_bonus_amount, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><small class="text-muted">Pairing Cap</small></td>
                                        <td><strong>₦{{ number_format($user->package->pairing_cap, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><small class="text-muted">Status</small></td>
                                        <td>
                                            <span class="badge {{ $user->package->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->package->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if($user->package->description)
                        <div class="mt-3">
                            <small class="text-muted">Description:</small>
                            <p class="mb-0 small">{{ $user->package->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-box text-light-gray fs-1 mb-2"></i>
                    <p class="text-muted mb-0">No package selected</p>
                    <button class="btn btn-sm btn-red mt-2" onclick="alert('Package selection coming soon!')">
                        Select Package
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Rank Details -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-award-fill text-warning me-2"></i>Rank Information</h5>
            </div>
            <div class="card-body">
                @if($user->rank)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light-warning border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rank-icon bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-award text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-warning">{{ $user->rank->name }}</h5>
                                        <small class="text-muted">Current Rank</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Level</small>
                                        <strong>{{ $user->rank->level }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Required PV</small>
                                        <strong>{{ number_format($user->rank->required_pv, 1) }} PV</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td><small class="text-muted">Cash Reward</small></td>
                                        <td><strong class="text-success">₦{{ number_format($user->rank->cash_reward, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><small class="text-muted">Other Rewards</small></td>
                                        <td><strong>{{ $user->rank->other_reward ?? 'N/A' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><small class="text-muted">Status</small></td>
                                        <td>
                                            <span class="badge {{ $user->rank->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->rank->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if($user->rank->description)
                        <div class="mt-3">
                            <small class="text-muted">Description:</small>
                            <p class="mb-0 small">{{ $user->rank->description }}</p>
                        </div>
                        @endif
                        
                        <!-- Next Rank Progress -->
                        @if($nextRank)
                        <div class="mt-4">
                            <h6 class="text-muted mb-2">Next Rank: {{ $nextRank->name }}</h6>
                            <div class="progress" style="height: 8px;">
                                @php
                                    $progress = ($user->total_pv / $nextRank->required_pv) * 100;
                                    $progress = min($progress, 100);
                                @endphp
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                {{ number_format($user->total_pv, 1) }} PV / {{ number_format($nextRank->required_pv, 1) }} PV needed
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-award text-light-gray fs-1 mb-2"></i>
                    <p class="text-muted mb-0">No rank achieved yet</p>
                    <small class="text-muted">Start building your network to achieve ranks</small>
                    
                    <!-- Show first rank as target if no rank achieved -->
                    @if($nextRank)
                    <div class="mt-4">
                        <h6 class="text-muted mb-2">First Rank Target: {{ $nextRank->name }}</h6>
                        <div class="progress" style="height: 8px;">
                            @php
                                $progress = ($user->total_pv / $nextRank->required_pv) * 100;
                                $progress = min($progress, 100);
                            @endphp
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progress }}%" 
                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            {{ number_format($user->total_pv, 1) }} PV / {{ number_format($nextRank->required_pv, 1) }} PV needed
                        </small>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-red { background-color: rgba(230, 51, 35, 0.08) !important; }
    .bg-light-teal { background-color: rgba(31, 163, 196, 0.08) !important; }
    .bg-light-purple { background-color: rgba(106, 90, 205, 0.08) !important; }
    .bg-light-warning { background-color: rgba(255, 193, 7, 0.08) !important; }
    .text-purple { color: #6a5acd !important; }
    .bg-purple { background-color: #6a5acd !important; }
    .bg-teal { background-color: #20c997 !important; }
    .bg-pink { background-color: #e83e8c !important; }
    
    .action-card {
        transition: transform 0.3s;
        border-radius: 12px;
    }
    
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .wallet-icon {
        transition: transform 0.3s;
    }
    
    .wallet-icon:hover {
        transform: scale(1.1);
    }
    
    .package-icon {
        box-shadow: 0 4px 15px rgba(230, 51, 35, 0.2);
    }
    
    .rank-icon {
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-card .card-body {
            padding: 1.25rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    function copyReferralCode(button) {
        const referralCode = document.getElementById('referralCode');
        referralCode.select();
        referralCode.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(referralCode.value).then(() => {
            // Save original content and styles
            const originalHTML = button.innerHTML;
            const originalClass = button.className;
            
            // Change button appearance
            button.innerHTML = '<i class="bi bi-check2"></i> Copied!';
            button.classList.remove('btn-red');
            button.classList.add('btn-success');
            
            // Restore after 2 seconds
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.className = originalClass; // restores all classes
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy referral code. Please try again.');
        });
    }
</script>
@endsection