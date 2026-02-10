@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-teal text-white">
                    <h4 class="mb-0">Complete Payment</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="alert alert-info">
                        <h5>Package: {{ $package->name }}</h5>
                        <p><strong>Amount:</strong> ₦{{ number_format($package->price, 2) }}</p>
                        <p><strong>PV:</strong> {{ $package->pv }}</p>
                        <p>Please select your preferred payment method:</p>
                    </div>

                    <form method="POST" action="{{ route('payment.process') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Payment Method *</label>
                            <div class="payment-methods">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" required>
                                    <label class="form-check-label" for="bank_transfer">
                                        <strong>Bank Transfer</strong><br>
                                        <small class="text-muted">Transfer to our bank account and upload proof</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="upline_wallet" value="upline_wallet" required>
                                    <label class="form-check-label" for="upline_wallet">
                                        <strong>Upline Wallet Payment</strong><br>
                                        <small class="text-muted">Your upline will pay from their registration wallet</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="online_payment" value="online_payment" required>
                                    <label class="form-check-label" for="online_payment">
                                        <strong>Online Payment</strong><br>
                                        <small class="text-muted">Pay instantly with card or bank transfer</small>
                                    </label>
                                </div>
                            </div>
                            @error('payment_method') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- Bank Transfer Details -->
                        <div id="bankTransferDetails" class="d-none mb-4">
                            <div class="alert alert-warning">
                                <h6>Bank Transfer Instructions:</h6>
                                <p><strong>Bank:</strong> Happylife Multipurpose Int'l Bank</p>
                                <p><strong>Account Number:</strong> 1234567890</p>
                                <p><strong>Account Name:</strong> Happylife Multipurpose Int'l</p>
                                <p>Please upload your proof of payment below:</p>
                            </div>
                            <div class="mb-3">
                                <label for="proof_of_payment" class="form-label">Proof of Payment *</label>
                                <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control">
                                @error('proof_of_payment') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Upline Wallet Info -->
                        <div id="uplineWalletInfo" class="d-none mb-4">
                            <div class="alert alert-info">
                                <p>Your upline will be notified to approve this payment from their registration wallet.</p>
                                <p>Upline: {{ Auth::user()->sponsor->name ?? 'Not found' }}</p>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">I agree to the payment terms and conditions</label>
                            @error('terms') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-teal w-100">Proceed with Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const bankTransferDetails = document.getElementById('bankTransferDetails');
    const uplineWalletInfo = document.getElementById('uplineWalletInfo');
    const proofInput = document.getElementById('proof_of_payment');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            bankTransferDetails.classList.add('d-none');
            uplineWalletInfo.classList.add('d-none');
            proofInput.required = false;

            if (this.value === 'bank_transfer') {
                bankTransferDetails.classList.remove('d-none');
                proofInput.required = true;
            } else if (this.value === 'upline_wallet') {
                uplineWalletInfo.classList.remove('d-none');
            }
        });
    });
});
</script>
@endsection