@extends('layouts.app')

@section('title', 'Complete Payment - Happylife Multipurpose Int\'l')

@section('content')
<div class="container py-5 py-lg-6">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <!-- ================= PAYMENT CARD ================= -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header text-white p-5 border-0"
                     style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
                    <div class="text-center">
                        <h2 class="fw-bold mb-3 text-danger">Complete Payment</h2>
                        <p class="mb-0 text-danger opacity-75">
                            Activate your account and start earning
                        </p>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-5">

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif


                    <!-- ================= PACKAGE SUMMARY ================= -->
                    <div class="card border-danger mb-5">
                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold text-danger mb-0">Package Summary</h5>
                                <span class="badge bg-teal">Selected</span>
                            </div>

                            @if($package)
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Package Name</small>
                                        <h4 class="fw-bold">{{ $package->name }}</h4>

                                        <small class="text-muted">Product Value</small>
                                        <h5 class="fw-bold">
                                            ₦{{ number_format($package->price, 2) }}
                                        </h5>
                                    </div>

                                    <div class="col-md-6">
                                        <small class="text-muted">PV Points</small>
                                        <h4 class="fw-bold">{{ $package->pv }} PV</h4>

                                        <small class="text-muted">Direct Bonus</small>
                                        <h5 class="fw-bold text-success">
                                            ₦{{ number_format($package->direct_bonus_amount, 2) }}
                                        </h5>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    Includes product worth ₦{{ number_format($package->price, 2) }}
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    Package not found. Contact support.
                                </div>
                            @endif
                        </div>
                    </div>


                    <!-- ================= PAYMENT METHOD ================= -->
                    <h5 class="fw-bold border-bottom pb-2 mb-4 text-danger">
                        Select Payment Method
                    </h5>

                    <div class="row g-3 mb-4">

                        <!-- Bank -->
                        <div class="col-md-6">
                            <div class="card payment-card h-100" data-method="bank_transfer">
                                <div class="card-body text-center p-4">
                                    <input type="radio" name="payment_method"
                                           class="payment-radio" value="bank_transfer" required>
                                    <i class="bi bi-bank fs-1 text-danger"></i>
                                    <h6 class="fw-bold">Bank Transfer</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Online -->
                        <div class="col-md-6">
                            <div class="card payment-card h-100" data-method="online_payment">
                                <div class="card-body text-center p-4">
                                    <input type="radio" name="payment_method"
                                           class="payment-radio" value="online_payment" required>
                                    <i class="bi bi-credit-card fs-1 text-danger"></i>
                                    <h6 class="fw-bold">Online Payment</h6>
                                </div>
                            </div>
                        </div>

                    </div>


                    @php
                        $bank = \App\Models\BankSetting::first();
                    @endphp

                    <!-- ================= PAYMENT DETAILS ================= -->
                    <div id="paymentDetails" style="display:none;">

                        <!-- Bank Transfer -->
                        <div id="bankTransferDetails" class="d-none">
                            <div class="alert alert-info">

                                <strong>Bank:</strong> {{ $bank->bank_name ?? 'N/A' }} <br>
                                <strong>Account No:</strong> {{ $bank->account_number ?? 'N/A' }} <br>
                                <strong>Name:</strong> {{ $bank->account_name ?? 'N/A' }}

                            </div>

                            <form method="POST"
                                  action="{{ route('payment.bank-transfer') }}"
                                  enctype="multipart/form-data"
                                  id="bankTransferForm">
                                @csrf

                                <input type="file" name="proof_of_payment"
                                       class="form-control mb-3" required>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input"
                                           id="terms_bank" required>
                                    <label>I confirm payment</label>
                                </div>

                                <button class="btn btn-danger w-100">
                                    Submit Proof
                                </button>
                            </form>
                        </div>


                        <!-- Online Payment -->
                        <div id="onlinePaymentOptions" class="d-none">

                            <div class="alert alert-warning">
                                Choose Gateway
                            </div>

                            <button id="proceedToPayment"
                                    class="btn btn-danger w-100">
                                Proceed to Payment
                            </button>
                        </div>
                    </div>


                    <!-- ================= TOTAL ================= -->
                    <div class="card bg-light mt-4">
                        <div class="card-body d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-danger">
                                ₦{{ $package ? number_format($package->price, 2) : '0.00' }}
                            </strong>
                        </div>
                    </div>


                    <!-- ================= BENEFITS ================= -->
                    <div class="alert alert-light mt-4">
                        <strong>Activation Benefits:</strong>
                        <ul class="mb-0">
                            <li>Dashboard Access</li>
                            <li>Earn Commissions</li>
                            <li>Claim Product</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- ================= JS ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const paymentCards = document.querySelectorAll('.payment-card');
    const paymentDetails = document.getElementById('paymentDetails');
    const bankDetails = document.getElementById('bankTransferDetails');
    const onlineOptions = document.getElementById('onlinePaymentOptions');

    paymentCards.forEach(card => {
        card.addEventListener('click', function () {

            paymentCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            const method = this.dataset.method;

            paymentDetails.style.display = 'block';
            bankDetails.classList.add('d-none');
            onlineOptions.classList.add('d-none');

            if (method === 'bank_transfer') {
                bankDetails.classList.remove('d-none');
            } else {
                onlineOptions.classList.remove('d-none');
            }
        });
    });

});
</script>


<!-- ================= STYLE ================= -->
<style>
.payment-card {
    cursor: pointer;
    transition: 0.3s;
}
.payment-card.selected {
    border: 2px solid #E63323;
}
.btn-danger {
    background: #E63323;
}
.bg-teal {
    background: #1FA3C4;
}
</style>

@endsection