@extends('layouts.member')

@section('title', 'My Profile - Happylife Multipurpose Int\'l')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-dark-gray">My Profile</h1>
                <p class="text-muted mb-0">View your account details and activity.</p>
            </div>
            <div>
                <a href="{{ route('member.profile.edit') }}" class="btn btn-red me-2">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                </a>

                <a href="{{ route('member.kyc.index') }}" class="btn btn-red">
                    <i class="bi bi-shield-check me-1"></i> Update KYC
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Profile Overview Card -->
<div class="row mb-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="avatar-wrapper bg-gradient-teal rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px;">
                        <span class="text-white fs-1 fw-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <h4 class="mb-1 text-dark-gray">{{ $user->name }}</h4>
                <p class="text-muted mb-2">Member since {{ $user->created_at->format('M Y') }}</p>
                <span class="badge bg-gradient-teal text-white badge-custom">
                    <i class="bi bi-award me-1"></i> {{ $user->package->name ?? 'No Package' }}
                </span>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Referral Code</span>
                    <span class="fw-bold text-red">{{ $user->referral_code ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Username</span>
                    <span class="fw-bold">{{ $user->username }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-success">{{ ucfirst($user->status) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-person-lines-fill text-teal-blue me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper bg-light-red rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-envelope-fill text-red"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Email Address</small>
                                <strong>{{ $user->email }}</strong>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success ms-2">Verified</span>
                                @else
                                    <span class="badge bg-warning">Unverified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper bg-light-teal rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-phone-fill text-teal-blue"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Phone Number</small>
                                <strong>{{ $user->phone ?? 'Not provided' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper bg-light-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-geo-alt-fill text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Country</small>
                                <strong>{{ $user->country ?? 'Not specified' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper bg-light-purple rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-building text-purple"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">State</small>
                                <strong>{{ $user->state ?? 'Not specified' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-arrow-up-circle-fill text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Sponsor</small>
                                <strong>{{ $user->sponsor->name ?? 'N/A' }} ({{ $user->sponsor_id ?? '' }})</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-diagram-2-fill text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Placement</small>
                                <strong>{{ $user->placement->name ?? 'N/A' }} ({{ ucfirst($user->placement_position) ?? '' }})</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Account Details -->
<div class="col-md-6 mb-4">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 text-dark-gray"><i class="bi bi-wallet2 text-teal-blue me-2"></i>Wallet Balances</h5>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                    <span>Amount: </span>
                    <span class="fw-bold text-teal-blue">₦{{ number_format($shoppingBalance, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-bar-chart-steps text-red me-2"></i>Network Summary</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                        <span>Direct Sponsors</span>
                        <span class="fw-bold">{{ $user->direct_sponsors_count ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                        <span>Left Team Members</span>
                        <span class="fw-bold">{{ $user->left_count }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                        <span>Right Team Members</span>
                        <span class="fw-bold">{{ $user->right_count }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                        <span>Total PV</span>
                        <span class="fw-bold">{{ number_format($user->total_pv, 1) }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                        <span>Left PV</span>
                        <span class="fw-bold">{{ number_format($user->left_pv, 1) }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                        <span>Right PV</span>
                        <span class="fw-bold">{{ number_format($user->right_pv, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection