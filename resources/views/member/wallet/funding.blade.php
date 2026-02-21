@extends('layouts.member')

@section('title', 'Fund Shopping Wallet - Happylife')

@section('content')
<div class="container-fluid px-4">
    {{-- Flash messages from session --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('member.wallet.index') }}" class="text-teal-blue text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Back to Wallets
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="mb-0 text-dark-gray">Fund Your Wallet</h4>
                    <p class="text-muted mb-0">Add money to your wallet to purchase products</p>
                </div>
                <div class="card-body p-4">
                    <!-- Payment Method Tabs -->
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
                        {{-- ========== ONLINE PAYMENT TAB ========== --}}
                        <div class="tab-pane fade show active" id="online" role="tabpanel">
                            <div id="paymentDetails">
                                <!-- Amount Input -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Amount (₦)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">₦</span>
                                        <input type="number" id="onlineAmount" class="form-control form-control-lg" min="100" step="0.01" placeholder="0.00" value="{{ old('amount') }}">
                                    </div>
                                    <div class="form-text text-muted">Minimum deposit: ₦100</div>
                                </div>

                                <!-- Gateway Selection Cards -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Choose Payment Gateway</label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="gateway-card card h-100 border-0 shadow-sm selected" data-gateway="paystack">
                                                <div class="card-body d-flex align-items-center">
                                                    <input type="radio" name="payment_gateway" value="paystack" class="gateway-radio me-3" checked>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/1f/Paystack.png" 
                                                             alt="Paystack" height="40" class="me-2">
                                                        <span class="fw-semibold">Paystack</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="gateway-card card h-100 border-0 shadow-sm" data-gateway="flutterwave">
                                                <div class="card-body d-flex align-items-center">
                                                    <input type="radio" name="payment_gateway" value="flutterwave" class="gateway-radio me-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://flutterwave.com/images/logo/full.svg" 
                                                             alt="Flutterwave" height="40" width="100" class="me-2">
                                                        <span class="fw-semibold">Flutterwave</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms_online" required>
                                        <label class="form-check-label text-muted" for="terms_online">
                                            I agree to the <a href="#" class="text-teal-blue">terms and conditions</a> and confirm that the amount is correct.
                                        </label>
                                    </div>
                                </div>

                                <!-- Proceed Button -->
                                <button type="button" id="proceedToPaymentBtn" class="btn btn-red w-100 py-3 rounded-pill fw-semibold">
                                    <i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway
                                </button>
                                <p class="text-muted small text-center mt-3 mb-0">
                                    You will be redirected to the secure payment page.
                                </p>
                            </div>
                        </div>

                        {{-- ========== BANK TRANSFER (MANUAL) TAB ========== --}}
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

                            <form action="{{ route('member.wallet.request') }}" method="POST" enctype="multipart/form-data" id="bankTransferForm">
                                @csrf
                                <input type="hidden" name="payment_method" value="bank_transfer">
                                <!-- 👇 Explicitly set wallet type to shopping -->
                                <input type="hidden" name="wallet_type" value="shopping">

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

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms_bank" required>
                                        <label class="form-check-label text-muted" for="terms_bank">
                                            I confirm that I have made the transfer to the account above.
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-red w-100 py-3 rounded-pill fw-semibold">
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
                                        @if(isset($req->wallet_type))
                                            <span class="badge bg-info text-dark ms-1">{{ ucfirst($req->wallet_type) }}</span>
                                        @endif
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

<!-- Payment Gateway SDKs -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://checkout.flutterwave.com/v3.js"></script>

<!-- Payment Handling Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gateway Card Selection
        const gatewayCards = document.querySelectorAll('.gateway-card');
        gatewayCards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.type === 'radio') return;
                const radio = this.querySelector('.gateway-radio');
                radio.checked = true;
                gatewayCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
            });

            const radio = card.querySelector('.gateway-radio');
            radio.addEventListener('change', function() {
                if (this.checked) {
                    gatewayCards.forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                }
            });
        });

        // Proceed to Payment
        const proceedBtn = document.getElementById('proceedToPaymentBtn');
        proceedBtn.addEventListener('click', function() {
            const amount = document.getElementById('onlineAmount').value;
            const gateway = document.querySelector('input[name="payment_gateway"]:checked')?.value;
            const termsChecked = document.getElementById('terms_online').checked;

            if (!amount || amount < 100) {
                alert('Please enter a valid amount (minimum ₦100).');
                return;
            }
            if (!gateway) {
                alert('Please select a payment gateway.');
                return;
            }
            if (!termsChecked) {
                alert('You must agree to the terms and conditions.');
                return;
            }

            proceedBtn.disabled = true;
            proceedBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Redirecting...';

            if (gateway === 'paystack') {
                initiatePaystack(amount);
            } else {
                initiateFlutterwave(amount);
            }
        });

        // ----- Paystack -----
        function initiatePaystack(amount) {
            const userEmail = '{{ Auth::user()->email }}';
            const userId = {{ Auth::user()->id }};
            const reference = 'FUND-' + Date.now() + '-' + userId;

            const handler = PaystackPop.setup({
                key: '{{ config("services.paystack.public_key") }}',
                email: userEmail,
                amount: amount * 100,
                currency: 'NGN',
                ref: reference,
                metadata: {
                    user_id: userId,
                    wallet_type: 'shopping'      // 👈 now funds shopping wallet
                },
                callback: function(response) {
                    processPayment(response.reference, amount, 'paystack');
                },
                onClose: function() {
                    proceedBtn.disabled = false;
                    proceedBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway';
                }
            });
            handler.openIframe();
        }

        // ----- Flutterwave -----
        function initiateFlutterwave(amount) {
            const userEmail = '{{ Auth::user()->email }}';
            const userId = {{ Auth::user()->id }};
            const reference = 'FUND-' + Date.now() + '-' + userId;

            FlutterwaveCheckout({
                public_key: '{{ config("services.flutterwave.public_key") }}',
                tx_ref: reference,
                amount: amount,
                currency: 'NGN',
                payment_options: 'card, banktransfer, ussd',
                customer: {
                    email: userEmail,
                    name: '{{ Auth::user()->name }}',
                },
                customizations: {
                    title: 'Happylife Multipurpose Int\'l',
                    description: 'Fund Shopping Wallet',   // 👈 updated description
                    logo: '{{ asset("images/logo.png") }}',
                },
                meta: {
                    user_id: userId,
                    wallet_type: 'shopping'       // 👈 now funds shopping wallet
                },
                callback: function(response) {
                    processPayment(response.tx_ref, amount, 'flutterwave');
                },
                onclose: function() {
                    proceedBtn.disabled = false;
                    proceedBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway';
                }
            });
        }

        // ----- Process Payment (AJAX to payment.success) -----
        function processPayment(reference, amount, gateway) {
            proceedBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

            fetch('{{ route("member.wallet.payment.success") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reference, amount, gateway, wallet_type: 'shopping' }) // 👈 explicit
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFlashMessage('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    showFlashMessage('danger', data.message);
                    proceedBtn.disabled = false;
                    proceedBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway';
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                showFlashMessage('danger', 'Network error. Please try again.');
                proceedBtn.disabled = false;
                proceedBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway';
            });
        }

        // Flash Message Helper
        function showFlashMessage(type, message) {
            const oldAlert = document.querySelector('.dynamic-alert');
            if (oldAlert) oldAlert.remove();

            const alertDiv = document.createElement('div');
            alertDiv.className = `dynamic-alert alert alert-${type} alert-dismissible fade show shadow-sm rounded-3 mb-4`;
            alertDiv.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const container = document.querySelector('.container-fluid.px-4');
            container.prepend(alertDiv);
        }

        // Bank Transfer: loading state
        const bankForm = document.getElementById('bankTransferForm');
        if (bankForm) {
            bankForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting...';
            });
        }

        // Auto-select first gateway
        if (gatewayCards.length > 0) {
            gatewayCards[0].classList.add('selected');
        }
    });
</script>

<style>
    .gateway-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent !important;
    }
    .gateway-card.selected {
        border-color: #dc3545 !important;
        background-color: #fef2f2;
    }
    .gateway-radio {
        width: 1.2em;
        height: 1.2em;
        accent-color: #dc3545;
    }
</style>
@endsection