@extends('layouts.member')

@section('title', 'Fund Wallet - Happylife')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <a href="{{ route('member.wallet.index') }}" class="text-teal-blue text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Back to Wallets
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="mb-0 text-dark-gray">Fund Your Registration Wallet</h4>
                    <p class="text-muted mb-0">Choose your preferred funding method</p>
                </div>
                <div class="card-body p-4">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-4" id="fundingTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="online-tab" data-bs-toggle="tab" data-bs-target="#online" type="button" role="tab">
                                <i class="bi bi-credit-card me-2"></i> Pay Online (Card/Bank)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab">
                                <i class="bi bi-bank me-2"></i> Bank Transfer (Manual)
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="fundingTabContent">
                        <!-- ONLINE PAYMENT TAB -->
                        <div class="tab-pane fade show active" id="online" role="tabpanel">
                            <form action="{{ route('member.wallet.pay.init') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Amount (₦)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">₦</span>
                                        <input type="number" name="amount" class="form-control form-control-lg" min="100" step="0.01" required placeholder="0.00">
                                    </div>
                                    <div class="form-text text-muted">Minimum deposit: ₦100</div>
                                </div>

                                <div class="alert alert-info bg-opacity-10 border-0" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    You are funding your <strong>Registration Wallet</strong>. Funds can be used to register new members under your network.
                                </div>

                                <button type="submit" class="btn btn-red w-100 py-3 rounded-pill fw-semibold">
                                    <i class="bi bi-lock me-2"></i> Proceed to Paystack
                                </button>
                            </form>
                        </div>

                        <!-- BANK TRANSFER (MANUAL) TAB -->
                        <div class="tab-pane fade" id="bank" role="tabpanel">
                            <!-- Admin Bank Details -->
                            <div class="bg-light p-4 rounded-3 mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-bank2 me-2 text-red"></i> Our Bank Account</h6>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Bank Name</small>
                                        <span class="fw-semibold">First Bank of Nigeria</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Account Name</small>
                                        <span class="fw-semibold">Happylife Multipurpose Int'l</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Account Number</small>
                                        <span class="fw-semibold text-red">2034567890</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-2 py-0" onclick="navigator.clipboard.writeText('2034567890')">
                                            <i class="bi bi-files"></i>
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Sort Code</small>
                                        <span class="fw-semibold">011234567</span>
                                    </div>
                                </div>
                                <div class="mt-3 small text-muted">
                                    <i class="bi bi-info-circle"></i> Transfer the exact amount and upload the proof below.
                                </div>
                            </div>

                            <form action="{{ route('member.wallet.request') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="payment_method" value="bank_transfer">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Amount Paid (₦)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">₦</span>
                                        <input type="number" name="amount" class="form-control" min="100" step="0.01" required placeholder="0.00">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Transaction ID / Reference</label>
                                    <input type="text" name="transaction_id" class="form-control" required placeholder="e.g. T1234567890">
                                    <div class="form-text text-muted">The reference number from your bank transfer receipt.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Upload Proof of Payment</label>
                                    <input type="file" name="proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required id="proofInput">
                                    <div class="form-text text-muted">JPG, PNG or PDF (max 2MB)</div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Additional Notes (Optional)</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Any extra information..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-teal-blue w-100 py-3 rounded-pill fw-semibold">
                                    <i class="bi bi-send me-2"></i> Submit Funding Request
                                </button>
                                <p class="text-muted small text-center mt-3 mb-0">
                                    Your request will be reviewed and approved within 24 hours.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Funding Requests -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-dark-gray"><i class="bi bi-clock-history me-2 text-warning"></i> Recent Funding Requests</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentRequests = App\Models\FundingRequest::where('user_id', auth()->id())
                                            ->latest()
                                            ->take(5)
                                            ->get();
                    @endphp
                    @if($recentRequests->count())
                        <div class="list-group list-group-flush">
                            @foreach($recentRequests as $req)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-semibold">₦{{ number_format($req->amount, 2) }}</span>
                                        <span class="badge bg-light text-dark ms-2">{{ $req->payment_method == 'online' ? 'Online' : 'Bank Transfer' }}</span>
                                    </div>
                                    <div>
                                        <span class="badge 
                                            @if($req->status == 'approved') bg-success
                                            @elseif($req->status == 'pending') bg-warning text-dark
                                            @else bg-danger @endif">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                        <small class="text-muted ms-3">{{ $req->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">No funding requests yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Optional: Display selected filename (Bootstrap version doesn't need complex script)
    document.getElementById('proofInput')?.addEventListener('change', function(e) {
        let fileName = e.target.files[0]?.name;
        // You can optionally show it somewhere; but Bootstrap shows it in the input
    });
</script>
@endsection