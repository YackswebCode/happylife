@extends('layouts.admin')

@section('title', 'View User - ' . $user->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">View User: {{ $user->name }}</h2>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <!-- User Info Cards -->
    <div class="row g-4">
        <!-- Basic Info -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-badge me-2 text-red"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Name:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td><code>{{ $user->username }}</code></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $user->phone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ ucfirst($user->gender ?? '—') }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $user->address ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-shield-check me-2 text-teal-blue"></i>Account Status</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Status:</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'active' => 'success',
                                        'inactive' => 'secondary',
                                        'suspended' => 'danger',
                                    ][$user->status] ?? 'warning';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($user->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                @if($user->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Email Verified:</th>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                    <small class="text-muted">{{ $user->email_verified_at->format('d M Y') }}</small>
                                @else
                                    <span class="badge bg-danger">Unverified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Registration Date:</th>
                            <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Activated At:</th>
                            <td>{{ $user->activated_at ? $user->activated_at->format('d M Y') : 'Not activated' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Package & Genealogy -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2 text-soft-cyan"></i>Package & Genealogy</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Package:</th>
                            <td>{{ $user->package->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Rank:</th>
                            <td>{{ $user->rank->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Sponsor:</th>
                            <td>{{ $user->sponsor->name ?? '—' }} <code>{{ $user->sponsor->username ?? '' }}</code></td>
                        </tr>
                        <tr>
                            <th>Placement:</th>
                            <td>{{ $user->placement->name ?? '—' }} ({{ $user->placement_position ?? '—' }})</td>
                        </tr>
                        <tr>
                            <th>Referral Code:</th>
                            <td><code>{{ $user->referral_code ?? '—' }}</code></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Wallet Balances -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2 text-success"></i>Wallet Balances</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">Commission Wallet:</th>
                            <td class="fw-bold">{{ number_format($user->commission_wallet_balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Registration Wallet:</th>
                            <td class="fw-bold">{{ number_format($user->registration_wallet_balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Shopping Wallet:</th>
                            <td class="fw-bold">{{ number_format($user->shopping_wallet_balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Rank Wallet:</th>
                            <td class="fw-bold">{{ number_format($user->rank_wallet_balance, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bonus Totals -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-graph-up me-2 text-red"></i>Bonus Totals</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3"><strong>Direct Bonus:</strong> {{ number_format($user->direct_bonus_total, 2) }}</div>
                        <div class="col-md-3"><strong>Level 2 Indirect:</strong> {{ number_format($user->indirect_level_2_bonus_total, 2) }}</div>
                        <div class="col-md-3"><strong>Level 3 Indirect:</strong> {{ number_format($user->indirect_level_3_bonus_total, 2) }}</div>
                        <div class="col-md-3"><strong>Matching PV:</strong> {{ number_format($user->matching_pv_bonus_total, 2) }}</div>
                        <div class="col-md-3"><strong>Rank Bonus:</strong> {{ number_format($user->rank_bonus_total, 2) }}</div>
                        <div class="col-md-3"><strong>Repurchase Bonus:</strong> {{ number_format($user->repurchase_bonus_total, 2) }}</div>
                        <div class="col-md-3"><strong>Lifestyle Bonus:</strong> {{ number_format($user->lifestyle_bonus_total, 2) }}</div>
                        <div class="col-md-3"><strong>Upgrade Bonus:</strong> {{ number_format($user->upgrade_bonus_total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection