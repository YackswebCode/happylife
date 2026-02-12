@extends('layouts.member')

@section('title', 'My Wallets - Happylife Multipurpose Int\'l')

@section('content')
<div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark-gray">My Wallets</h1>
            <p class="text-muted">Manage your funds and transactions</p>
        </div>
        <a href="{{ route('member.wallet.funding') }}" class="btn btn-red rounded-pill px-4 py-2 shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Fund Wallet
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
    <!-- Wallet Cards Grid -->
    <div class="row g-4 mb-5">
        @php
            $walletTypes = [
                'commission'   => ['name' => 'Commission', 'icon' => 'bi-cash-stack', 'color' => 'bg-teal-blue'],
                'registration' => ['name' => 'Registration', 'icon' => 'bi-person-plus', 'color' => 'bg-soft-cyan'],
                'shopping'     => ['name' => 'Shopping',     'icon' => 'bi-cart',      'color' => 'bg-dark-gray'],
            ];
        @endphp

        @foreach($walletTypes as $key => $info)
            @php $wallet = $wallets[$key] ?? null; @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 {{ $info['color'] }} text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="small opacity-75 mb-1">{{ $info['name'] }} Wallet</p>
                                <h3 class="display-6 fw-bold">₦{{ number_format($wallet->balance ?? 0, 2) }}</h3>
                                <span class="badge bg-white text-dark bg-opacity-25">
                                    Locked: ₦{{ number_format($wallet->locked_balance ?? 0, 2) }}
                                </span>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi {{ $info['icon'] }} fs-4"></i>
                            </div>
                        </div>

                        {{-- CRITICAL FIX: Registration wallet links to funding, not a non‑existent detail page --}}
                        @if($key === 'registration')
                            <a href="{{ route('member.wallet.funding') }}" 
                               class="text-white text-decoration-none small fw-semibold mt-3 d-inline-block">
                                Fund Wallet <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('member.wallet.' . $key) }}" 
                               class="text-white text-decoration-none small fw-semibold mt-3 d-inline-block">
                                View Details <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Transactions & Pending Requests -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark-gray">
                            <i class="bi bi-clock-history me-2 text-red"></i> Recent Transactions
                        </h5>
                        <a href="{{ route('member.wallet.transactions') }}" class="text-red small">View all</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count())
                        <div class="list-group list-group-flush">
                            @foreach($recentTransactions as $tx)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle p-2 me-3 {{ $tx->type == 'credit' ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                            <i class="bi {{ $tx->type == 'credit' ? 'bi-arrow-up' : 'bi-arrow-down' }} {{ $tx->type == 'credit' ? 'text-success' : 'text-danger' }}"></i>
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $tx->description }}</p>
                                            <small class="text-muted">{{ $tx->created_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold {{ $tx->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $tx->formatted_amount }}
                                        </span>
                                        <br>
                                        <small class="text-muted text-capitalize">{{ $tx->wallet->type }} wallet</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-receipt display-4"></i>
                            <p class="mt-3">No transactions yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-dark-gray">
                        <i class="bi bi-hourglass-split me-2 text-warning"></i> Pending Funding
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingRequests->count())
                        <div class="list-group list-group-flush">
                            @foreach($pendingRequests as $req)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">₦{{ number_format($req->amount, 2) }}</span>
                                    <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-wallet2 display-5"></i>
                            <p class="mt-2">No pending requests.</p>
                        </div>
                    @endif
                    <a href="{{ route('member.wallet.funding') }}" class="btn btn-teal-blue w-100 mt-3 rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i> Make a Deposit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection