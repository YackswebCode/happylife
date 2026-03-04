@extends('layouts.member')

@section('title','Electricity Bill - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Display Session Messages -->
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

    <!-- Placeholder for AJAX messages -->
    <div id="ajaxAlert"></div>

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
                    <div class="mb-3">
                        <label for="disco" class="form-label">Select DISCO</label>
                        <select name="disco_id" id="disco" class="form-select" required>
                            <option value="">Select Disco</option>
                            @foreach($discos as $disco)
                                <option value="{{ $disco->id }}" data-service="{{ $disco->code }}">{{ $disco->name }}</option>
                            @endforeach
                        </select>
                        @error('disco_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="meter_number" class="form-label">Meter Number</label>
                        <input type="text" class="form-control" name="meter_number" id="meter_number" placeholder="Enter meter number" required>
                        @error('meter_number')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="meter_type" class="form-label">Meter Type</label>
                        <select name="meter_type" id="meter_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="prepaid">Prepaid</option>
                            <option value="postpaid">Postpaid</option>
                        </select>
                        @error('meter_type')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₦)</label>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount" required>
                        @error('amount')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" readonly required>
                    </div>

                    <button type="button" class="btn btn-happylife-teal w-100" id="validateMeterBtn">Validate Meter</button>
                    <button type="submit" class="btn btn-happylife-dark w-100 mt-2">Pay Bill</button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <h5 class="mb-3 fw-bold">Recent Payments</h5>
            <div class="list-group">
                @forelse($recentElectricity as $trx)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $trx->provider->name ?? 'Unknown' }}</strong><br>
                            Meter: {{ $trx->meter_number ?? 'N/A' }}
                        </div>
                        <span class="badge bg-success rounded-pill">₦{{ number_format($trx->amount,2) }}</span>
                    </div>
                @empty
                    <p class="text-secondary">No recent transactions.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('validateMeterBtn').addEventListener('click', function() {
    const discoId = document.getElementById('disco').value;
    const meterNumber = document.getElementById('meter_number').value;
    const meterType = document.getElementById('meter_type').value;
    const amount = document.getElementById('amount').value;

    const ajaxAlert = document.getElementById('ajaxAlert');
    ajaxAlert.innerHTML = '';

    if (!discoId || !meterNumber || !meterType || !amount) {
        ajaxAlert.innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">All fields are required.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        return;
    }

    fetch("{{ route('member.vtu.electricity.validate') }}", {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({disco_id: discoId, meter_number: meterNumber, meter_type: meterType, amount: amount})
    })
    .then(res => res.json())
    .then(data => {
        if(data.error){
            ajaxAlert.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.error+'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else if(data.success){
            document.getElementById('customer_name').value = data.customer_name;
            ajaxAlert.innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Meter validated successfully! Customer: '+data.customer_name+'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
    })
    .catch(err => {
        console.error(err);
        ajaxAlert.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Server error. Try again.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    });
});
</script>
@endpush
@endsection