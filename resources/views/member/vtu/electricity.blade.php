@extends('layouts.member')

@section('title', 'Electricity Bill - Happylife')

@section('content')
<div class="container-fluid py-4">
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

                <form action="{{ route('member.vtu.electricity.purchase') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Distribution Company</label>
                            <select name="disco" id="disco" class="form-select" required>
                                <option value="">Select Disco</option>
                                @foreach($discos ?? [] as $disco)
                                    <option value="{{ $disco->id }}">{{ $disco->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meter Number</label>
                            <input type="text" name="meter_number" class="form-control" placeholder="e.g. 12345678901" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meter Type</label>
                            <select name="meter_type" class="form-select" required>
                                <option value="prepaid">Prepaid</option>
                                <option value="postpaid">Postpaid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Amount (₦)</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" name="amount" class="form-control" min="100" step="100" placeholder="1000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Your Commission Wallet</label>
                            <div class="form-control bg-light" readonly>
                                ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-happylife-dark btn-lg w-100 py-3">
                                <i class="bi bi-lightning-charge me-2"></i> Pay Bill
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
                                        <small class="fw-bold">{{ $tx->disco_name }}</small>
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
@endsection