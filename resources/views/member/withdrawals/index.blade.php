@extends('layouts.member')

@section('title', 'Withdraw Funds - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-happylife-dark">Withdraw Funds</h1>
            <p class="text-secondary">Request withdrawal from your commission wallet</p>
        </div>
        <a href="{{ route('member.withdraw.history') }}" class="btn btn-outline-happylife-teal">
            <i class="bi bi-clock-history me-2"></i> Withdrawal History
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card product-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-happylife-red bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-cash-stack fs-3 text-happylife-red"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Request Payout</h5>
                        <p class="text-secondary mb-0">Funds will be sent to your bank account after admin approval</p>
                    </div>
                </div>

                @if(isset($pendingWithdrawal) && $pendingWithdrawal)
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>You have a pending withdrawal request!</strong><br>
                            Reference: {{ $pendingWithdrawal->reference }} – ₦{{ number_format($pendingWithdrawal->amount, 2) }}<br>
                            Please wait for admin approval before submitting a new request.
                            <a href="{{ route('member.withdraw.history') }}" class="alert-link ms-2">View Status</a>
                        </div>
                    </div>
                @else
                    <form action="{{ route('member.withdraw.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <!-- Commission Wallet Balance -->
                            <div class="col-12">
                                <div class="bg-happylife-light p-3 rounded-3 mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Available Commission Wallet:</span>
                                        <span class="h4 fw-bold text-happylife-red mb-0">₦{{ number_format($commissionBalance, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Withdrawal Amount (₦)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₦</span>
                                    <input type="number" name="amount" id="amount" class="form-control" min="2000" step="100" value="{{ old('amount') }}" required>
                                </div>
                                <small class="text-secondary">Minimum ₦2,000. Admin fee: 2%</small>
                            </div>

                            <!-- Fee & Net (dynamic preview) -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Admin Fee (2%)</label>
                                <div class="form-control bg-light" id="fee_preview">₦0.00</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">You will receive</label>
                                <div class="form-control bg-light fw-bold text-happylife-red" id="net_preview">₦0.00</div>
                            </div>

                            <!-- Bank Details -->
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold border-bottom pb-2">Bank Account Details</h6>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" placeholder="e.g. First Bank" value="{{ old('bank_name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Account Name</label>
                                <input type="text" name="account_name" class="form-control" placeholder="Full name on account" value="{{ old('account_name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Account Number</label>
                                <input type="text" name="account_number" class="form-control" placeholder="10-digit number" value="{{ old('account_number') }}" required>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-happylife-red btn-lg w-100 py-3">
                                    <i class="bi bi-send-check me-2"></i> Submit Withdrawal Request
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Sidebar: Recent Withdrawals -->
        <div class="col-lg-4">
            <div class="card product-card p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-clock-history me-2"></i> Recent Requests
                </h5>
                @if($recentWithdrawals->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentWithdrawals as $wd)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="fw-bold">₦{{ number_format($wd->amount, 2) }}</small>
                                        <br>
                                        <small class="text-secondary">{{ $wd->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <div class="text-end">
                                        @if($wd->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($wd->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($wd->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($wd->status == 'cancelled')
                                            <span class="badge bg-secondary">Cancelled</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('member.withdraw.history') }}" class="btn btn-sm btn-outline-happylife-teal">View All</a>
                    </div>
                @else
                    <div class="text-center py-4 text-secondary">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>No withdrawal requests yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live calculation of fee and net amount
    document.getElementById('amount').addEventListener('input', function(e) {
        let amount = parseFloat(e.target.value) || 0;
        let fee = amount * 0.02;
        let net = amount - fee;
        document.getElementById('fee_preview').innerHTML = '₦' + fee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('net_preview').innerHTML = '₦' + net.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    });
</script>
@endpush
@endsection