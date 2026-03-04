@extends('layouts.member')

@section('title', 'Electricity Bill - Happylife')

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

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('member.vtu.index') }}" class="btn btn-outline-happylife-teal me-3">
            <i class="bi bi-arrow-left me-1"></i> Back to VTU
        </a>
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Pay Electricity Bill</h1>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card product-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-happylife-dark bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-lightbulb fs-3 text-happylife-dark"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Electricity Bill Payment</h5>
                        <p class="text-secondary mb-0">Prepaid & Postpaid</p>
                    </div>
                </div>

                <!-- Validation Alert Placeholder -->
                <div id="validationAlert" class="alert d-none" role="alert"></div>

                <form action="{{ route('member.vtu.electricity.purchase') }}" method="POST" id="electricityForm">
                    @csrf
                    <input type="hidden" name="customer_name" id="customer_name" value="">
                    <input type="hidden" name="disco_id" id="disco_id" value="">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Distribution Company</label>
                            <select name="disco" id="disco" class="form-select @error('disco') is-invalid @enderror" required>
                                <option value="">Select Disco</option>
                                @foreach($discos ?? [] as $disco)
                                    <option value="{{ $disco->id }}">
                                        {{ $disco->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('disco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meter Number</label>
                            <input type="text" name="meter_number" id="meter_number"
                                   class="form-control @error('meter_number') is-invalid @enderror"
                                   placeholder="e.g. 12345678901"
                                   value="{{ old('meter_number') }}" required>
                            @error('meter_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meter Type</label>
                            <select name="meter_type" id="meter_type"
                                    class="form-select @error('meter_type') is-invalid @enderror" required>
                                <option value="prepaid" {{ old('meter_type') == 'prepaid' ? 'selected' : '' }}>Prepaid</option>
                                <option value="postpaid" {{ old('meter_type') == 'postpaid' ? 'selected' : '' }}>Postpaid</option>
                            </select>
                            @error('meter_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Amount (₦)</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" name="amount" id="amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       min="100" step="100"
                                       placeholder="1000"
                                       value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Your Commission Wallet</label>
                            <div class="form-control bg-light" readonly>
                                ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="button" id="validateBtn" class="btn btn-danger btn-lg w-100 py-3">
                                <i class="bi bi-check-circle me-2"></i> Validate Meter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Validation Result Section (hidden initially) -->
                <div id="validationResult" class="mt-4 p-3 bg-light rounded-3 d-none">
                    <h6 class="fw-bold mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Meter Validated</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Customer Name</small>
                            <strong id="displayCustomerName"></strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Address</small>
                            <strong id="displayAddress"></strong>
                        </div>
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

        <!-- RIGHT SIDEBAR (unchanged) -->
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
                    <i class="bi bi-clock-history me-2"></i> Recent Payments
                </h5>
                @if(isset($recentElectricity) && $recentElectricity->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentElectricity as $tx)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="fw-bold">{{ $tx->provider->name ?? 'Electricity' }}</small>
                                        <br>
                                        <small class="text-secondary">{{ $tx->meter_number }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-happylife-red">
                                            ₦{{ number_format($tx->amount, 2) }}
                                        </span>
                                        <br>
                                        <span class="badge bg-success">Success</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary text-center py-3 mb-0">No recent payments.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const validateBtn = document.getElementById('validateBtn');
const discoSelect = document.getElementById('disco');
const meterNumberInput = document.getElementById('meter_number');
const meterTypeSelect = document.getElementById('meter_type');
const amountInput = document.getElementById('amount');
const customerNameInput = document.getElementById('customer_name');
const discoIdInput = document.getElementById('disco_id');
const form = document.getElementById('electricityForm');
const validationAlert = document.getElementById('validationAlert');
const validationResult = document.getElementById('validationResult');
const displayCustomerName = document.getElementById('displayCustomerName');
const displayAddress = document.getElementById('displayAddress');
const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
const editDetailsBtn = document.getElementById('editDetailsBtn');

function showAlert(message, type = 'danger') {
    validationAlert.className = `alert alert-${type} alert-dismissible fade show`;
    validationAlert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    validationAlert.classList.remove('d-none');
    validationAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function resetValidationState() {
    validationResult.classList.add('d-none');
    validateBtn.disabled = false;
    validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate Meter';
}

validateBtn.addEventListener('click', function(e) {
    e.preventDefault();

    const discoId = discoSelect.value;
    const meterNumber = meterNumberInput.value.trim();
    const meterType = meterTypeSelect.value;
    const amount = amountInput.value.trim();

    if (!discoId) return showAlert('Please select a distribution company.', 'warning');
    if (!meterNumber) return showAlert('Please enter your meter number.', 'warning');
    if (!amount || parseFloat(amount) < 100) return showAlert('Minimum ₦100 required.', 'warning');

    // Disable button and show loading
    validateBtn.disabled = true;
    validateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Validating...';
    validationAlert.classList.add('d-none');
    validationResult.classList.add('d-none');

    fetch('{{ route('member.vtu.electricity.validate') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            disco_id: discoId,
            meter_number: meterNumber,
            meter_type: meterType,
            amount: amount
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Store validated data in hidden fields
            customerNameInput.value = data.customer_name || '';
            discoIdInput.value = discoId;

            // Display validation result
            displayCustomerName.textContent = data.customer_name || 'N/A';
            displayAddress.textContent = data.address || 'N/A';
            validationResult.classList.remove('d-none');

            // Disable validate button further (can re-enable with Edit)
            validateBtn.disabled = true;
            validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validated';
        } else {
            showAlert(data.error || 'Validation failed.', 'danger');
            resetValidationState();
        }
    })
    .catch(err => {
        console.error(err);
        showAlert('Validation failed. Please try again.', 'danger');
        resetValidationState();
    });
});

// Confirm Payment: submit the form
confirmPaymentBtn.addEventListener('click', function() {
    form.submit();
});

// Edit Details: re-enable the form and hide result section
editDetailsBtn.addEventListener('click', function() {
    resetValidationState();
    // Optionally clear hidden fields
    customerNameInput.value = '';
    discoIdInput.value = '';
});
</script>
@endpush