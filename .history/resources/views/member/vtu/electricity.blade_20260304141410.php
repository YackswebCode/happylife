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
                                    <option value="{{ $disco->id }}" data-service="{{ $disco->service_code ?? '' }}">
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
                            <input type="text" name="meter_number" id="meter_number" class="form-control @error('meter_number') is-invalid @enderror" 
                                   placeholder="e.g. 12345678901" value="{{ old('meter_number') }}" required>
                            @error('meter_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meter Type</label>
                            <select name="meter_type" id="meter_type" class="form-select @error('meter_type') is-invalid @enderror" required>
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
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       min="100" step="100" placeholder="1000" value="{{ old('amount') }}" required>
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
                                <i class="bi bi-check-circle me-2"></i> Validate & Pay Bill
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
                    <span class="fw-bold text-happylife-red">₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}</span>
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
                                        <span class="fw-bold text-happylife-red">₦{{ number_format($tx->amount, 2) }}</span>
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

    validateBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const discoId = discoSelect.value;
        const meterNumber = meterNumberInput.value.trim();
        const meterType = meterTypeSelect.value;
        const amount = amountInput.value.trim();

        if (!discoId) {
            alert('Please select a distribution company.');
            return;
        }
        if (!meterNumber) {
            alert('Please enter your meter number.');
            return;
        }
        if (!amount || parseFloat(amount) < 100) {
            alert('Please enter a valid amount (minimum ₦100).');
            return;
        }

        // Disable button and show loading
        validateBtn.disabled = true;
        validateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Validating...';

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
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                validateBtn.disabled = false;
                validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Pay Bill';
                return;
            }
            if (data.success) {
                // Set hidden fields
                customerNameInput.value = data.customer_name || '';
                discoIdInput.value = discoId;

                // Submit the form
                form.submit();
            } else {
                alert('Validation failed. Please check your details.');
                validateBtn.disabled = false;
                validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Pay Bill';
            }
        })
        .catch(error => {
            console.error(error);
            alert('An error occurred during validation.');
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Validate & Pay Bill';
        });
    });
</script>
@endpush
@endsection