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
                        <h3 class="stat-value mb-0 text-teal-blue">₦{{ number_format($stats['commission_balance'], 2) }}</h3>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-red" onclick="alert('Withdraw feature coming soon!')">
                                <i class="bi bi-cash-coin me-1"></i> Withdraw
                            </button>
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
                    <div class="col-lg-3 col-md-6">
                        <a href="#" class="card action-card border-0 text-decoration-none" onclick="alert('Coming soon!')">
                            <div class="card-body text-center p-4">
                                <div class="icon-wrapper bg-gradient-teal rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-diagram-3-fill fs-4 text-white"></i>
                                </div>
                                <h6 class="mb-2 text-dark-gray">Genealogy Tree</h6>
                                <small class="text-muted">View your network structure</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="#" class="card action-card border-0 text-decoration-none" onclick="alert('Coming soon!')">
                            <div class="card-body text-center p-4">
                                <div class="icon-wrapper bg-gradient-red rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-cart-fill fs-4 text-white"></i>
                                </div>
                                <h6 class="mb-2 text-dark-gray">Shopping Mall</h6>
                                <small class="text-muted">Shop products & repurchase</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="#" class="card action-card border-0 text-decoration-none" onclick="alert('Coming soon!')">
                            <div class="card-body text-center p-4">
                                <div class="icon-wrapper bg-teal-blue rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-phone-fill fs-4 text-white"></i>
                                </div>
                                <h6 class="mb-2 text-dark-gray">VTU Services</h6>
                                <small class="text-muted">Buy airtime, data, bills</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="#" class="card action-card border-0 text-decoration-none" onclick="alert('Coming soon!')">
                            <div class="card-body text-center p-4">
                                <div class="icon-wrapper bg-soft-cyan rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-person-plus-fill fs-4 text-white"></i>
                                </div>
                                <h6 class="mb-2 text-dark-gray">Refer Someone</h6>
                                <small class="text-muted">Grow your network</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-clock-history text-red me-2"></i>Recent Activities</h5>
                <button class="btn btn-sm btn-outline-red" onclick="alert('Coming soon!')">View All</button>
            </div>
            <div class="card-body">
                @if($recent_activities->count() > 0)
                <div class="activity-timeline">
                    @foreach($recent_activities as $activity)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="activity-icon me-3">
                                    <i class="bi {{ $activity['icon'] }} {{ $activity['color'] }}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-dark-gray">{{ $activity['title'] }}</h6>
                                    <p class="text-muted mb-1 small">{{ $activity['description'] }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $activity['date']->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light {{ $activity['color'] }} px-3 py-2">
                                    {{ $activity['amount'] > 0 ? '+' : '' }}₦{{ number_format($activity['amount'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-activity fs-1 text-light-gray"></i>
                    </div>
                    <h6 class="text-muted mb-2">No recent activities</h6>
                    <p class="text-muted small">Your activities will appear here</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Wallet Summary -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-wallet2 text-teal-blue me-2"></i>Wallet Summary</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                        <span class="d-flex align-items-center">
                            <span class="wallet-icon bg-red rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bi bi-cash-stack text-white"></i>
                            </span>
                            <span>
                                <h6 class="mb-0">Commission</h6>
                                <small class="text-muted">Withdrawable earnings</small>
                            </span>
                        </span>
                        <span class="fw-bold text-teal-blue">₦{{ number_format($stats['commission_balance'], 2) }}</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                        <span class="d-flex align-items-center">
                            <span class="wallet-icon bg-teal-blue rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bi bi-person-plus text-white"></i>
                            </span>
                            <span>
                                <h6 class="mb-0">Registration</h6>
                                <small class="text-muted">For new registrations</small>
                            </span>
                        </span>
                        <span class="fw-bold text-teal-blue">₦{{ number_format($stats['registration_balance'], 2) }}</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                        <span class="d-flex align-items-center">
                            <span class="wallet-icon bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bi bi-trophy text-white"></i>
                            </span>
                            <span>
                                <h6 class="mb-0">Rank Awards</h6>
                                <small class="text-muted">Achievement bonuses</small>
                            </span>
                        </span>
                        <span class="fw-bold text-teal-blue">₦{{ number_format($stats['rank_balance'], 2) }}</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                        <span class="d-flex align-items-center">
                            <span class="wallet-icon bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="bi bi-cart text-white"></i>
                            </span>
                            <span>
                                <h6 class="mb-0">Shopping</h6>
                                <small class="text-muted">For repurchases</small>
                            </span>
                        </span>
                        <span class="fw-bold text-teal-blue">₦{{ number_format($stats['shopping_balance'], 2) }}</span>
                    </div>
                </div>
                
                <!-- Package Info -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="text-muted mb-3">Package Information</h6>
                    <div class="card bg-light-red border-0">
                        <div class="card-body">
                            @if($user->package)
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
                            <div class="mt-3">
                                <small class="text-muted">Includes product worth ₦{{ number_format($user->package->price, 2) }}</small>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="bi bi-box text-light-gray fs-1 mb-2"></i>
                                <p class="text-muted mb-0">No package selected</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Network Stats -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-pie-chart-fill text-red me-2"></i>Network Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light-red h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-arrow-left-circle-fill fs-1 text-red"></i>
                                </div>
                                <h2 class="text-red mb-2">{{ $stats['left_count'] }}</h2>
                                <p class="text-muted mb-0">Left Team</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light-teal h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-arrow-right-circle-fill fs-1 text-teal-blue"></i>
                                </div>
                                <h2 class="text-teal-blue mb-2">{{ $stats['right_count'] }}</h2>
                                <p class="text-muted mb-0">Right Team</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light-purple h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-diagram-2-fill fs-1 text-purple"></i>
                                </div>
                                <h2 class="text-purple mb-2">{{ $stats['left_count'] + $stats['right_count'] }}</h2>
                                <p class="text-muted mb-0">Total Team</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-red { background-color: rgba(230, 51, 35, 0.08) !important; }
    .bg-light-teal { background-color: rgba(31, 163, 196, 0.08) !important; }
    .bg-light-purple { background-color: rgba(106, 90, 205, 0.08) !important; }
    .text-purple { color: #6a5acd !important; }
    
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
@endsection