@extends('layouts.member')

@section('title', 'Electricity Bill - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Session Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

                <div id="validationAlert" class="alert d-none" role="alert"></div>

                <form action="{{ route('member.vtu.electricity.purchase') }}" method="POST" id="electricityForm">
                    @csrf
                    <input type="hidden" name="customer_name" id="customer_name">
                    <input type="hidden" name="disco_id" id="disco_id">

                    <div class="row g-3">

                        <!-- Distribution Company -->
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

                        <!-- Meter Number -->
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

                        <!-- Meter Type -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meter Type</label>
                            <select name="meter_type" id="meter_type"
                                    class="form-select @error('meter_type') is-invalid @enderror" required>
                                <option value="prepaid" {{ old('meter_type') == 'prepaid' ? 'selected' : '' }}>Prepaid</option>
                                <option value="postpaid" {{ old('meter_type') == 'postpaid' ? 'selected' : '' }}>Postpaid</option>
                            </select>
                        </div>

                        <!-- Amount -->
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
                        </div>

                        <!-- Wallet -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Your Commission Wallet</label>
                            <div class="form-control bg-light">
                                ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                            </div>
                        </div>

                        <!-- Validate Button -->
                        <div class="col-12 mt-4">
                            <button type="button" id="validateBtn"
                                    class="btn btn-danger btn-lg w-100 py-3">
                                <i class="bi bi-check-circle me-2"></i>
                                Validate & Pay Bill
                            </button>
                        </div>

                    </div>
                </form>
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

function showAlert(message, type = 'danger') {
    validationAlert.className = `alert alert-${type} alert-dismissible fade show`;
    validationAlert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    validationAlert.classList.remove('d-none');
    validationAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

    validateBtn.disabled = true;
    validateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Validating...';
    validationAlert.classList.add('d-none');

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
            customerNameInput.value = data.customer_name || '';
            discoIdInput.value = discoId;
            form.submit();
        } else {
            showAlert(data.error || 'Validation failed.', 'danger');
            resetButton();
        }

    })
    .catch(err => {
        showAlert('Validation failed. Try again.', 'danger');
        resetButton();
    });

    function resetButton() {
        validateBtn.disabled = false;
        validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Pay Bill';
    }
});
</script>
@endpush