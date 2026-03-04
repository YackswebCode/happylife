@extends('layouts.member')

@section('title', 'Cable TV - Happylife')

@section('content')
<div class="container-fluid py-4">
    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="session-success">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="session-error">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Message --}}
    <div id="validation-message"></div>

    {{-- Header --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('member.vtu.index') }}" class="btn btn-outline-happylife-teal me-3">
            <i class="bi bi-arrow-left me-1"></i> Back to VTU
        </a>
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Cable TV Subscription</h1>
    </div>

    <div class="row g-4">
        {{-- Subscription Form --}}
        <div class="col-lg-8">
            <div class="card product-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-happylife-cyan bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-tv fs-3 text-happylife-cyan"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Subscribe to Cable TV</h5>
                        <p class="text-secondary mb-0">DSTV, GOTV, Startimes</p>
                    </div>
                </div>

                <form action="{{ route('member.vtu.cable.purchase') }}" method="POST" id="cableForm">
                    @csrf
                    <input type="hidden" name="customer_name" id="customer_name" value="">
                    <div class="row g-3">

                        {{-- Provider --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Provider</label>
                            <select name="provider" id="provider" class="form-select @error('provider') is-invalid @enderror" required>
                                <option value="">Select Provider</option>
                                @foreach($cableProviders ?? [] as $provider)
                                    <option value="{{ $provider->id }}" {{ old('provider') == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provider')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Smart Card --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Smart Card / IUC Number</label>
                            <input type="text" name="smart_card" id="smart_card" class="form-control @error('smart_card') is-invalid @enderror" 
                                   placeholder="e.g. 1234567890" value="{{ old('smart_card') }}" required>
                            @error('smart_card')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Plan / Bouquet --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bouquet / Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                                <option value="">First select a provider</option>
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Amount --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Amount (₦)</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" name="amount" id="amount" class="form-control" readonly>
                            </div>
                        </div>

                        {{-- Commission Wallet --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Your Commission Wallet</label>
                            <div class="form-control bg-light" readonly>
                                ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 mt-4">
                            <button type="button" id="validateBtn" class="btn btn-danger btn-lg w-100 py-3">
                                <i class="bi bi-check-circle me-2"></i> Validate & Subscribe
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar: Wallet & Recent --}}
        <div class="col-lg-4">
            <div class="card product-card p-4 mb-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-wallet2 me-2"></i> Wallet Balance
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Commission Wallet:</span>
                    <span class="fw-bold text-happylife-red">₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}</span>
                </div>
            </div>

            <div class="card product-card p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-clock-history me-2"></i> Recent Subscriptions
                </h5>
                @if(isset($recentCable) && $recentCable->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentCable as $tx)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="fw-bold">{{ $tx->provider->name ?? 'Cable' }}</small>
                                        <br>
                                        <small class="text-secondary">{{ $tx->smart_card }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-happylife-red">₦{{ number_format($tx->amount, 2) }}</span>
                                        <br>
                                        @if($tx->status === 'success')
                                            <span class="badge bg-success">Success</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary text-center py-3 mb-0">No recent subscriptions.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const providerSelect = document.getElementById('provider');
    const smartCardInput = document.getElementById('smart_card');
    const planSelect = document.getElementById('plan_id');
    const amountInput = document.getElementById('amount');
    const validateBtn = document.getElementById('validateBtn');
    const customerNameInput = document.getElementById('customer_name');
    const validationMessage = document.getElementById('validation-message');

    // Clear validation messages
    function showMessage(type, message) {
        validationMessage.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    // Load cable plans when provider changes
    providerSelect.addEventListener('change', function() {
        const providerId = this.value;
        planSelect.innerHTML = '<option value="">Loading bouquets...</option>';
        amountInput.value = '';
        customerNameInput.value = '';

        if (!providerId) {
            planSelect.innerHTML = '<option value="">First select a provider</option>';
            return;
        }

        fetch(`{{ route('member.vtu.cable.plans') }}?provider_id=${providerId}`)
            .then(response => response.json())
            .then(plans => {
                if (plans.error) {
                    planSelect.innerHTML = '<option value="">Error loading plans</option>';
                    showMessage('danger', plans.error);
                    return;
                }
                planSelect.innerHTML = '<option value="">Select bouquet</option>';
                plans.forEach(plan => {
                    const option = document.createElement('option');
                    option.value = plan.id || plan.plan_code;
                    option.textContent = `${plan.name} - ₦${plan.amount}`;
                    option.dataset.amount = plan.amount;
                    planSelect.appendChild(option);
                });
            })
            .catch(() => {
                planSelect.innerHTML = '<option value="">Error loading plans</option>';
                showMessage('danger', 'Failed to load bouquets. Try again.');
            });
    });

    // Update amount when plan changes
    planSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        amountInput.value = selected.dataset.amount || '';
        customerNameInput.value = '';
        validationMessage.innerHTML = '';
    });

    // Validate smart card & submit
    validateBtn.addEventListener('click', function(e) {
        e.preventDefault();
        validationMessage.innerHTML = '';

        const providerId = providerSelect.value;
        const smartCard = smartCardInput.value.trim();
        const planId = planSelect.value;

        if (!providerId) return showMessage('warning', 'Please select a provider.');
        if (!smartCard) return showMessage('warning', 'Please enter your smart card / IUC number.');
        if (!planId) return showMessage('warning', 'Please select a bouquet.');

        validateBtn.disabled = true;
        validateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Validating...';

        fetch('{{ route('member.vtu.validate.smartcard') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ provider_id: providerId, smart_card: smartCard, plan_id: planId })
        })
        .then(res => res.json())
        .then(data => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Subscribe';

            if (data.error) return showMessage('danger', data.error);
            if (data.success) {
                customerNameInput.value = data.customer_name || '';
                showMessage('success', `Smart card valid! Customer: ${data.customer_name}`);
                // Submit after a short delay to allow user to see name
                setTimeout(() => document.getElementById('cableForm').submit(), 1000);
            }
        })
        .catch(() => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Subscribe';
            showMessage('danger', 'An error occurred during validation. Try again.');
        });
    });
});
</script>
@endpush
@endsection