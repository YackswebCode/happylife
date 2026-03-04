@extends('layouts.member')

@section('title', 'Buy Airtime - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Session Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('member.vtu.index') }}" class="btn btn-outline-happylife-teal me-3">
            <i class="bi bi-arrow-left me-1"></i> Back to VTU
        </a>
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Buy Airtime</h1>
    </div>

    <div class="row g-4">
        <!-- Airtime Purchase Form -->
        <div class="col-lg-8">
            <div class="card product-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-happylife-red bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-phone-fill fs-3 text-happylife-red"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Purchase Airtime</h5>
                        <p class="text-secondary mb-0">Instant top-up for any network</p>
                    </div>
                </div>

                <form action="{{ route('member.vtu.airtime.purchase') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Network Provider -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Network Provider</label>
                            <select name="network" class="form-select @error('network') is-invalid @enderror" required>
                                <option value="">Select Network</option>
                                @foreach($networks ?? [] as $network)
                                    <option value="{{ $network->id }}" {{ old('network') == $network->id ? 'selected' : '' }}>
                                        {{ $network->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('network')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   placeholder="08012345678" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Amount (₦)</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       min="50" step="50" placeholder="100" value="{{ old('amount') }}" required>
                            </div>
                            <small class="text-secondary">Minimum ₦50</small>
                            @error('amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Commission Wallet Balance -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Your Commission Wallet</label>
                            <div class="form-control bg-light" readonly>
                                ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-happylife-red btn-lg w-100 py-3">
                                <i class="bi bi-lightning-charge me-2"></i> Buy Airtime
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Wallet & Recent Transactions Sidebar -->
        <div class="col-lg-4">
            <div class="card product-card p-4 mb-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-wallet2 me-2"></i> Wallet Balance
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Commission Wallet:</span>
                    <span class="fw-bold text-happylife-red">
                        ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                    </span>
                </div>
                <div class="mt-3">
                    <a href="{{ route('member.wallet.funding') }}" class="btn btn-sm btn-outline-happylife-teal w-100">
                        <i class="bi bi-plus-circle me-1"></i> Fund Wallet
                    </a>
                </div>
            </div>

            <div class="card product-card p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-clock-history me-2"></i> Recent Airtime
                </h5>
                @if(isset($recentAirtime) && $recentAirtime->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentAirtime as $tx)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="fw-bold">{{ $tx->phone }}</small>
                                        <br>
                                        <small class="text-secondary">{{ $tx->created_at->format('M d, h:i A') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-happylife-red">₦{{ number_format($tx->amount, 2) }}</span>
                                        <br>
                                        @if($tx->status == 'success')
                                            <span class="badge bg-success">Success</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary text-center py-3 mb-0">No recent airtime purchases.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection