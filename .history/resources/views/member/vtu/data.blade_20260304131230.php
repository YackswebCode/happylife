@extends('layouts.member')

@section('title', 'Buy Data - Happylife')

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
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Buy Data Bundle</h1>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card product-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-happylife-teal bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-wifi fs-3 text-happylife-teal"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Purchase Data</h5>
                        <p class="text-secondary mb-0">Choose your network and plan</p>
                    </div>
                </div>

                <form action="{{ route('member.vtu.data.purchase') }}" method="POST" id="dataForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Network Provider</label>
                            <select name="network" id="network" class="form-select @error('network') is-invalid @enderror" required>
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
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   placeholder="08012345678" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Data Plan</label>
                            <select name="plan_code" id="plan_id" class="form-select @error('plan_code') is-invalid @enderror" required>
                                <option value="">First select a network</option>
                            </select>
                            @error('plan_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Amount (₦)</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" name="amount" id="amount" class="form-control" readonly>
                            </div>
                            <small class="text-secondary">Auto-calculated from plan</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Your Commission Wallet</label>
                            <div class="form-control bg-light" readonly>
                                ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-danger btn-lg w-100 py-3">
                                <i class="bi bi-lightning-charge me-2"></i> Buy Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
            </div>
            <div class="card product-card p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-clock-history me-2"></i> Recent Data
                </h5>
                @if(isset($recentData) && $recentData->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentData as $tx)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="fw-bold">{{ $tx->description ?? 'Data' }}</small>
                                        <br>
                                        <small class="text-secondary">{{ $tx->phone }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-happylife-red">₦{{ number_format($tx->amount, 2) }}</span>
                                        <br>
                                        <span class="badge bg-success">Success</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary text-center py-3 mb-0">No recent data purchases.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('network').addEventListener('change', function() {
        const networkId = this.value;
        const planSelect = document.getElementById('plan_id');
        const amountInput = document.getElementById('amount');

        // Clear current options
        planSelect.innerHTML = '<option value="">Loading plans...</option>';
        amountInput.value = '';

        if (!networkId) {
            planSelect.innerHTML = '<option value="">First select a network</option>';
            return;
        }

        fetch(`{{ route('member.vtu.plans') }}?network_id=${networkId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    planSelect.innerHTML = '<option value="">Error loading plans</option>';
                    console.error(data.error);
                    return;
                }
                planSelect.innerHTML = '<option value="">Select a plan</option>';
                data.forEach(plan => {
                    const option = document.createElement('option');
                    option.value = plan.plan_code;   // use plan_code as value
                    option.textContent = `${plan.name} - ₦${plan.amount}`;
                    option.dataset.amount = plan.amount;
                    planSelect.appendChild(option);
                });
            })
            .catch(err => {
                planSelect.innerHTML = '<option value="">Error loading plans</option>';
                console.error(err);
            });
    });

    document.getElementById('plan_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const amount = selected.dataset.amount || 0;
        document.getElementById('amount').value = amount;
    });
</script>
@endpush
@endsection