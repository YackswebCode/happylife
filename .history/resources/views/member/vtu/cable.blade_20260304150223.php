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

    {{-- Validation Alert Placeholder --}}
    <div id="validationAlert" class="alert d-none" role="alert"></div>

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

                        {{-- Validate Button --}}
                        <div class="col-12 mt-4">
                            <button type="button" id="validateBtn" class="btn btn-danger btn-lg w-100 py-3">
                                <i class="bi bi-check-circle me-2"></i> Validate & Continue
                            </button>
                        </div>

                    </div>
                </form>

                {{-- Validation Result Section (hidden initially) --}}
                <div id="validationResult" class="mt-4 p-3 bg-light rounded-3 d-none">
                    <h6 class="fw-bold mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Smart Card Validated</h6>
                    <div class="row g-2">
                        <div class="col-md-12">
                            <small class="text-secondary d-block">Customer Name</small>
                            <strong id="displayCustomerName"></strong>
                        </div>
                        {{-- Additional info can be displayed here if needed --}}
                    </div>
                    <button type="button" id="confirmPaymentBtn" class="btn btn-success btn-lg w-100 mt-3 py-3">
                        <i class="bi bi-lightning-charge me-2"></i> Confirm Payment
                    </button>
                    <button type="button" id="editDetailsBtn" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-pencil me-1"></i> Edit Details
                    </button>
                </div>

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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const providerSelect = document.getElementById('provider');
    const smartCardInput = document.getElementById('smart_card');
    const planSelect = document.getElementById('plan_id');
    const amountInput = document.getElementById('amount');
    const validateBtn = document.getElementById('validateBtn');
    const customerNameInput = document.getElementById('customer_name');
    const validationAlert = document.getElementById('validationAlert');
    const validationResult = document.getElementById('validationResult');
    const displayCustomerName = document.getElementById('displayCustomerName');
    const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
    const editDetailsBtn = document.getElementById('editDetailsBtn');

    // Helper to show Bootstrap alert
    function showAlert(message, type = 'danger') {
        validationAlert.className = `alert alert-${type} alert-dismissible fade show`;
        validationAlert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        validationAlert.classList.remove('d-none');
        validationAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Reset validation state (hide result, enable validate button)
    function resetValidation() {
        validationResult.classList.add('d-none');
        validateBtn.disabled = false;
        validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Continue';
        customerNameInput.value = '';
    }

    // Load cable plans when provider changes
    providerSelect.addEventListener('change', function() {
        const providerId = this.value;
        planSelect.innerHTML = '<option value="">Loading bouquets...</option>';
        amountInput.value = '';
        resetValidation();

        if (!providerId) {
            planSelect.innerHTML = '<option value="">First select a provider</option>';
            return;
        }

        fetch(`{{ route('member.vtu.cable.plans') }}?provider_id=${providerId}`)
            .then(response => response.json())
            .then(plans => {
                if (plans.error) {
                    planSelect.innerHTML = '<option value="">Error loading plans</option>';
                    showAlert(plans.error, 'danger');
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
                showAlert('Failed to load bouquets. Try again.', 'danger');
            });
    });

    // Update amount when plan changes
    planSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        amountInput.value = selected.dataset.amount || '';
        resetValidation();
    });

    // Smart card validation
    validateBtn.addEventListener('click', function(e) {
        e.preventDefault();
        validationAlert.classList.add('d-none');
        validationResult.classList.add('d-none');

        const providerId = providerSelect.value;
        const smartCard = smartCardInput.value.trim();
        const planId = planSelect.value;

        if (!providerId) return showAlert('Please select a provider.', 'warning');
        if (!smartCard) return showAlert('Please enter your smart card / IUC number.', 'warning');
        if (!planId) return showAlert('Please select a bouquet.', 'warning');

        validateBtn.disabled = true;
        validateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Validating...';

        fetch('{{ route('member.vtu.validate.smartcard') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                provider_id: providerId,
                smart_card: smartCard,
                plan_id: planId
            })
        })
        .then(res => res.json())
        .then(data => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Continue';

            if (data.error) {
                showAlert(data.error, 'danger');
                return;
            }

            if (data.success) {
                // Fill hidden field
                customerNameInput.value = data.customer_name || '';

                // Display result
                displayCustomerName.textContent = data.customer_name || 'N/A';
                validationResult.classList.remove('d-none');

                // Optionally disable the validate button (user can edit later)
                validateBtn.disabled = true;
                validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validated';
            } else {
                showAlert('Validation failed. Please check your details.', 'danger');
            }
        })
        .catch(err => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Continue';
            showAlert('An error occurred during validation. Try again.', 'danger');
        });
    });

    // Confirm payment: submit the form
    confirmPaymentBtn.addEventListener('click', function() {
        document.getElementById('cableForm').submit();
    });

    // Edit details: re-enable form and hide result
    editDetailsBtn.addEventListener('click', function() {
        resetValidation();
    });
});
</script>
@endpush