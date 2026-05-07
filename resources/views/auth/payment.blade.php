@extends('layouts.app')

@section('title', 'Complete Payment - Happylife Multipurpose Int\'l')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow border-0 rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header text-center text-white py-4"
                     style="background: linear-gradient(135deg, #1FA3C4, #3DB7D6);">
                    <h3 class="fw-bold mb-1" style="color: #c0392b;">Complete Payment</h3>
                    <p class="mb-0" style="color: #e74c3c;">Activate your account securely</p>
                </div>

                <div class="card-body p-4 p-lg-5">

                    {{-- FLASH MESSAGES --}}
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- PACKAGE SUMMARY --}}
                    @if($package)
                    <div class="mb-4 p-3 bg-light rounded-3">
                        <h5 class="fw-bold text-danger mb-3">
                            <i class="fas fa-box-open me-2"></i>Package Summary
                        </h5>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1 text-muted">Package</p>
                                <p class="fw-bold">{{ $package->name }}</p>
                            </div>
                            <div class="col-3">
                                <p class="mb-1 text-muted">Amount</p>
                                <p class="fw-bold">₦{{ number_format($package->price, 2) }}</p>
                            </div>
                            <div class="col-3">
                                <p class="mb-1 text-muted">PV</p>
                                <p class="fw-bold">{{ $package->pv }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- PAYMENT METHOD SELECTION --}}
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-money-bill-wave me-2 text-danger"></i>Select Payment Method
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card payment-card border-2 shadow-sm h-100" data-method="bank">
                                <div class="card-body text-center py-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" value="bank" id="methodBank">
                                    </div>
                                    <i class="fas fa-university fa-2x text-danger mb-2"></i>
                                    <h6 class="mb-0">Bank Transfer</h6>
                                    <small class="text-muted">Upload proof of payment</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card payment-card border-2 shadow-sm h-100" data-method="online">
                                <div class="card-body text-center py-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" value="online" id="methodOnline">
                                    </div>
                                    <i class="fas fa-credit-card fa-2x text-danger mb-2"></i>
                                    <h6 class="mb-0">Online Payment</h6>
                                    <small class="text-muted">Paystack / Flutterwave</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $bank = \App\Models\BankSetting::first();
                    @endphp

                    {{-- DETAILS SECTION --}}
                    <div id="details" style="display:none;">

                        {{-- BANK TRANSFER BOX --}}
                        <div id="bankBox" class="d-none">
                            <div class="alert alert-info border-0 rounded-3 shadow-sm">
                                <h6 class="fw-bold"><i class="fas fa-building-columns me-2"></i>Bank Account Details</h6>
                                <p class="mb-1"><strong>Bank:</strong> {{ $bank->bank_name ?? 'Not set' }}</p>
                                <p class="mb-1"><strong>Account Number:</strong> {{ $bank->account_number ?? 'Not set' }}</p>
                                <p class="mb-1"><strong>Account Name:</strong> {{ $bank->account_name ?? 'Not set' }}</p>
                            </div>

                            <form method="POST" action="{{ route('payment.bank-transfer') }}" enctype="multipart/form-data" id="bankTransferForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload Proof of Payment</label>
                                    <input type="file" name="proof_of_payment" class="form-control" accept="image/*" required>
                                    <div class="form-text text-muted">Please upload a clear screenshot or photo of your transfer receipt.</div>
                                    <img id="proofPreview" class="mt-2 img-thumbnail" style="max-height:150px; display:none;" />
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="confirmBank" required>
                                    <label class="form-check-label" for="confirmBank">
                                        I confirm that I have made the transfer and the details above are correct.
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-danger w-100 btn-lg" id="bankSubmitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" id="bankSpinner"></span>
                                    <i class="fas fa-paper-plane me-1"></i> Submit Payment Proof
                                </button>
                            </form>
                        </div>

                        {{-- ONLINE PAYMENT BOX --}}
                        <div id="onlineBox" class="d-none">
                            <div class="alert alert-warning border-0 rounded-3 shadow-sm">
                                <i class="fas fa-globe me-2"></i> Select your preferred payment gateway
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="card gateway-card border-2 h-100 shadow-sm" data-gateway="paystack">
                                        <div class="card-body text-center py-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gateway" value="paystack" id="gatewayPaystack" checked>
                                            </div>
                                            <img src="https://paystack.com/assets/img/logos/paystack-logo.png" alt="Paystack" style="height:30px;" class="mb-2">
                                            <p class="mb-0 fw-bold">Paystack</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card gateway-card border-2 h-100 shadow-sm" data-gateway="flutterwave">
                                        <div class="card-body text-center py-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gateway" value="flutterwave" id="gatewayFlutterwave">
                                            </div>
                                            <img src="https://flutterwave.com/images/logo/full-logo.png" alt="Flutterwave" style="height:30px;" class="mb-2">
                                            <p class="mb-0 fw-bold">Flutterwave</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms_online" required>
                                <label class="form-check-label" for="terms_online">
                                    I agree to the terms and conditions
                                </label>
                            </div>

                            <button id="payBtn" class="btn btn-danger w-100 btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status" id="paySpinner"></span>
                                <i class="fas fa-lock me-1"></i> Proceed to Payment
                            </button>
                        </div>
                    </div>

                    {{-- TOTAL --}}
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Total Amount</h5>
                        <h4 class="text-danger fw-bold mb-0">
                            ₦{{ number_format($package->price ?? 0, 2) }}
                        </h4>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- External Scripts --}}
<script src="https://js.paystack.co/v2/inline.js"></script>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script>
document.addEventListener('DOMContentLoaded', function () {
    /* =============================================
     *  Payment Method Card Selection
     * ============================================= */
    const cards = document.querySelectorAll('.payment-card');
    const details = document.getElementById('details');
    const bankBox = document.getElementById('bankBox');
    const onlineBox = document.getElementById('onlineBox');
    const payBtn = document.getElementById('payBtn');
    const bankForm = document.getElementById('bankTransferForm');
    const proofInput = document.querySelector('input[name="proof_of_payment"]');
    const proofPreview = document.getElementById('proofPreview');

    cards.forEach(card => {
        card.addEventListener('click', function () {
            cards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;

            details.style.display = 'block';
            bankBox.classList.add('d-none');
            onlineBox.classList.add('d-none');

            if (this.dataset.method === 'bank') {
                bankBox.classList.remove('d-none');
            } else {
                onlineBox.classList.remove('d-none');
            }
        });
    });

    // Gateway card selection
    const gateways = document.querySelectorAll('.gateway-card');
    gateways.forEach(g => {
        g.addEventListener('click', function () {
            gateways.forEach(x => x.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    // Image preview for bank proof
    if (proofInput) {
        proofInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    proofPreview.src = e.target.result;
                    proofPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                proofPreview.style.display = 'none';
            }
        });
    }

    // Bank transfer form loading
    bankForm.addEventListener('submit', function (e) {
        const confirmBox = document.getElementById('confirmBank');
        if (!confirmBox.checked) {
            e.preventDefault();
            alert('Please confirm that you have made the transfer.');
            return false;
        }
        document.getElementById('bankSpinner').classList.remove('d-none');
        document.getElementById('bankSubmitBtn').disabled = true;
    });

    /* =============================================
     *  ONLINE PAYMENT – BUTTON HANDLER
     * ============================================= */
    payBtn.addEventListener('click', function () {
        const gateway = document.querySelector('input[name="gateway"]:checked').value;
        const terms = document.getElementById('terms_online').checked;

        if (!terms) {
            alert('Please agree to the terms and conditions.');
            return;
        }

        if (gateway === 'paystack') {
            payWithPaystack();
        } else {
            payWithFlutterwave();
        }
    });

    function payWithPaystack() {
        const handler = PaystackPop.setup({
            key: '{{ config("services.paystack.public_key") }}',
            email: '{{ Auth::user()->email }}',
            amount: {{ $package->price ?? 0 }} * 100,
            currency: 'NGN',
            ref: 'PS-' + Date.now(),
            callback: function (response) {
                // Send to backend for activation & MLM
                activateUser(response.reference, 'paystack', {{ $package->id ?? 'null' }}, {{ $package->price ?? 0 }});
            },
            onClose: function () {
                alert('Payment window closed. You can try again.');
            }
        });
        handler.openIframe();
    }

    function payWithFlutterwave() {
        const txRef = 'FLW-' + Date.now();
        FlutterwaveCheckout({
            public_key: '{{ config("services.flutterwave.public_key") }}',
            tx_ref: txRef,
            amount: {{ $package->price ?? 0 }},
            currency: 'NGN',
            customer: {
                email: '{{ Auth::user()->email }}',
                name: '{{ Auth::user()->name }}'
            },
            callback: function (data) {
                activateUser(data.tx_ref, 'flutterwave', {{ $package->id ?? 'null' }}, {{ $package->price ?? 0 }});
            },
            onclose: function () {
                alert('Payment window closed. You can try again.');
            }
        });
    }

    /**
     * POST to /payment/activate to record the payment and execute MLM distribution.
     * On success, redirect to login page.
     */
    function activateUser(reference, gateway, packageId, amount) {
        // Show loading state on the button
        payBtn.disabled = true;
        payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Activating...';

        fetch('{{ route('payment.activate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                reference: reference,
                gateway: gateway,
                package_id: packageId,
                amount: amount
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Server error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert('Activation failed. Please contact support.');
                resetPayButton();
            }
        })
        .catch(error => {
            console.error(error);
            alert('An error occurred. Please try again.');
            resetPayButton();
        });
    }

    function resetPayButton() {
        payBtn.disabled = false;
        payBtn.innerHTML = '<i class="fas fa-lock me-1"></i> Proceed to Payment';
    }
});
</script>

<style>
.payment-card, .gateway-card {
    cursor: pointer;
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
    border-radius: 0.75rem;
}
.payment-card:hover, .gateway-card:hover {
    border-color: #adb5bd;
    transform: translateY(-2px);
}
.payment-card.selected,
.gateway-card.selected {
    border-color: #E63323 !important;
    background-color: #fff5f5;
    box-shadow: 0 0 0 3px rgba(230,51,35,0.1);
}
.btn-danger {
    background-color: #E63323;
    border-color: #E63323;
}
.btn-danger:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}
.spinner-border-sm {
    vertical-align: middle;
}
label.form-check-label {
    cursor: pointer;
}
</style>
@endsection