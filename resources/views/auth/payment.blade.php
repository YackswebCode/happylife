@extends('layouts.app')

@section('title', 'Complete Payment - Happylife Multipurpose Int\'l')

@section('content')
<div class="container py-5 py-lg-6">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Payment Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-gradient text-white p-5 border-0" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
                    <div class="text-center">
                        <h2 class="h1 fw-bold mb-3 text-danger">Complete Payment</h2>
                        <p class="mb-0 opacity-75 text-danger">Activate your account and start earning</p>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    <!-- Package Summary -->
                    <div class="card border-danger mb-5">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-danger">Package Summary</h5>
                                <span class="badge bg-teal text-white">Selected</span>
                            </div>
                            
                            @if($package)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Package Name</small>
                                            <h4 class="fw-bold">{{ $package->name }}</h4>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Product Value</small>
                                            <h5 class="fw-bold">₦{{ number_format($package->price, 2) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">PV Points</small>
                                            <h4 class="fw-bold">{{ $package->pv }} PV</h4>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Direct Sponsor Bonus</small>
                                            <h5 class="fw-bold text-success">₦{{ number_format($package->direct_bonus_amount, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <div class="d-flex">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <small>Includes product worth ₦{{ number_format($package->price, 2) }} that can be claimed at your selected pickup center</small>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Package not found. Please contact support.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4 border-bottom pb-2" style="color: #E63323;">
                            <i class="bi bi-credit-card me-2"></i>Select Payment Method
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <!-- Bank Transfer -->
                            <div class="col-md-6">
                                <div class="payment-method-card">
                                    <div class="card border-2 h-100 payment-card" data-method="bank_transfer">
                                        <div class="card-body text-center p-4">
                                            <div class="form-check d-flex justify-content-start">
                                                <input class="form-check-input payment-radio" type="radio" 
                                                       name="payment_method" id="bank_transfer" value="bank_transfer" required>
                                            </div>
                                            <i class="bi bi-bank text-danger fs-1 mb-3"></i>
                                            <h6 class="fw-bold mb-2">Bank Transfer</h6>
                                            <small class="text-muted">Transfer to our account and upload proof</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Online Payment -->
                            <div class="col-md-6">
                                <div class="payment-method-card">
                                    <div class="card border-2 h-100 payment-card" data-method="online_payment">
                                        <div class="card-body text-center p-4">
                                            <div class="form-check d-flex justify-content-start">
                                                <input class="form-check-input payment-radio" type="radio" 
                                                       name="payment_method" id="online_payment" value="online_payment" required>
                                            </div>
                                            <i class="bi bi-credit-card-2-front text-danger fs-1 mb-3"></i>
                                            <h6 class="fw-bold mb-2">Online Payment</h6>
                                            <small class="text-muted">Pay instantly with card or bank transfer</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Details -->
                        <div id="paymentDetails" style="display: none;">
                            <!-- Bank Transfer Details -->
                            <div id="bankTransferDetails" class="d-none">
                                <div class="alert alert-info">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Bank Transfer Instructions</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Bank:</strong></p>
                                            <p class="mb-3">Happylife Multipurpose Bank</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Account Number:</strong></p>
                                            <p class="mb-3">1234567890</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-1"><strong>Account Name:</strong></p>
                                            <p class="mb-3">Happylife Multipurpose Int'l</p>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-danger"><strong>Important:</strong> Include your username (<strong>{{ Auth::user()->username }}</strong>) as payment reference</p>
                                </div>
                                
                                <form method="POST" action="{{ route('payment.bank-transfer') }}" enctype="multipart/form-data" id="bankTransferForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="proof_of_payment" class="form-label fw-semibold">Upload Proof of Payment *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-upload text-muted"></i>
                                            </span>
                                            <input type="file" name="proof_of_payment" id="proof_of_payment" 
                                                   class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                        </div>
                                        <small class="text-muted">Upload screenshot or receipt of your transfer (Max: 2MB)</small>
                                    </div>
                                    
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms_bank" required>
                                        <label class="form-check-label" for="terms_bank">
                                            I confirm that I have made the bank transfer and uploaded the correct proof *
                                        </label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-danger btn-lg w-100 py-3 fw-bold">
                                        <i class="bi bi-upload me-2"></i> Submit Proof of Payment
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Online Payment Options -->
                            <div id="onlinePaymentOptions" class="d-none">
                                <div class="alert alert-warning">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Select Payment Gateway</h6>
                                    <p>Choose your preferred payment gateway to complete your payment securely.</p>
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="gateway-option">
                                            <div class="card border-2 h-100 gateway-card" data-gateway="paystack">
                                                <div class="card-body text-center p-4">
                                                    <div class="form-check d-flex justify-content-start">
                                                        <input class="form-check-input gateway-radio" type="radio" 
                                                               name="payment_gateway" id="paystack" value="paystack" checked>
                                                    </div>
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/1/1f/Paystack.png" 
                                                         alt="Paystack" height="40" class="mb-3">
                                                    <h6 class="fw-bold mb-2">Paystack</h6>
                                                    <small class="text-muted">Card, Bank Transfer, USSD</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="gateway-option">
                                            <div class="card border-2 h-100 gateway-card" data-gateway="flutterwave">
                                                <div class="card-body text-center p-4">
                                                    <div class="form-check d-flex justify-content-start">
                                                        <input class="form-check-input gateway-radio" type="radio" 
                                                               name="payment_gateway" id="flutterwave" value="flutterwave">
                                                    </div>
                                                    <img src="https://flutterwave.com/images/logo/full.svg" 
                                                         alt="Flutterwave" height="40" width="100" class="mb-3">
                                                    <h6 class="fw-bold mb-2">Flutterwave</h6>
                                                    <small class="text-muted">Card, Mobile Money, Bank Transfer</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms_online" required>
                                    <label class="form-check-label" for="terms_online">
                                        I agree to the payment terms and conditions *
                                    </label>
                                </div>
                                
                                <button type="button" id="proceedToPayment" class="btn btn-danger btn-lg w-100 py-3 fw-bold">
                                    <i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway
                                </button>
                            </div>
                        </div>

                        <!-- Payment Amount -->
                        <div class="card bg-light border-0 mt-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0">Total Amount Due:</h5>
                                    <h2 class="fw-bold text-danger mb-0">
                                        ₦{{ $package ? number_format($package->price, 2) : '0.00' }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div class="alert alert-light border">
                        <h6 class="fw-bold mb-3" style="color: #E63323;">
                            <i class="bi bi-gift me-2"></i>Activation Benefits
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <small>Immediate access to dashboard</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <small>Start earning commissions</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <small>Claim your product package</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <small>Access to training materials</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Note -->
            <div class="alert alert-light border mt-4 text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-shield-check text-success me-2"></i>
                    <small class="text-muted">All payments are secured with 256-bit SSL encryption</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paystack Inline JS -->
<script src="https://js.paystack.co/v2/inline.js"></script>

<!-- Flutterwave Inline JS -->
<script src="https://checkout.flutterwave.com/v3.js"></script>

<style>
    .bg-teal {
        background-color: #1FA3C4 !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #E63323;
        box-shadow: 0 0 0 0.25rem rgba(230, 51, 35, 0.25);
    }
    
    .btn-danger {
        background-color: #E63323;
        border-color: #E63323;
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        background-color: #d6281a;
        border-color: #d6281a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(230, 51, 35, 0.3);
    }
    
    .payment-card, .gateway-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border-color: #dee2e6 !important;
    }
    
    .payment-card.selected, .gateway-card.selected {
        border-color: #E63323 !important;
        background-color: rgba(230, 51, 35, 0.05);
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(230, 51, 35, 0.1);
    }
    
    .payment-card:hover, .gateway-card:hover {
        border-color: #E63323 !important;
        background-color: rgba(230, 51, 35, 0.02);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(230, 51, 35, 0.1);
    }
    
    .form-check-input {
        width: 20px;
        height: 20px;
        margin-top: 0;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #E63323;
        border-color: #E63323;
    }
    
    .payment-method-card .form-check,
    .gateway-option .form-check {
        margin-bottom: 15px;
    }
    
    @media (max-width: 768px) {
        .py-lg-6 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
        
        .card-header, .card-body {
            padding: 2rem !important;
        }
        
        .payment-method-card, .gateway-option {
            margin-bottom: 1rem;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
        }
    }
    
    /* Clear visual indicator for selected options */
    .selected-card-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        background-color: #E63323;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }
    
    /* Better spacing for radio buttons */
    .payment-method-card .card-body,
    .gateway-option .card-body {
        position: relative;
        padding-top: 15px;
    }
.alert-container {
    margin-top: 100px;
    min-width: 300px;
    max-width: 500px;
    z-index: 1050;
}

.alert {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentCards = document.querySelectorAll('.payment-card');
    const gatewayCards = document.querySelectorAll('.gateway-card');
    const paymentDetails = document.getElementById('paymentDetails');
    const bankTransferDetails = document.getElementById('bankTransferDetails');
    const onlinePaymentOptions = document.getElementById('onlinePaymentOptions');
    const proceedToPaymentBtn = document.getElementById('proceedToPayment');
    
    // Handle payment method card selection
    paymentCards.forEach(card => {
        const method = card.getAttribute('data-method');
        const radio = card.querySelector('.payment-radio');
        
        // Click on card selects the radio
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on the radio itself
            if (e.target.type === 'radio') return;
            
            radio.checked = true;
            
            // Update card styling
            paymentCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            
            // Show corresponding details
            bankTransferDetails.classList.add('d-none');
            onlinePaymentOptions.classList.add('d-none');
            paymentDetails.style.display = 'none';

            if (radio.checked) {
                paymentDetails.style.display = 'block';
                
                if (method === 'bank_transfer') {
                    bankTransferDetails.classList.remove('d-none');
                } else if (method === 'online_payment') {
                    onlinePaymentOptions.classList.remove('d-none');
                    
                    // Auto-select first gateway
                    const firstGatewayCard = document.querySelector('.gateway-card');
                    if (firstGatewayCard) {
                        firstGatewayCard.click();
                    }
                }
            }
        });
        
        // Click on radio also updates styling
        radio.addEventListener('change', function() {
            if (this.checked) {
                paymentCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                
                // Show corresponding details
                bankTransferDetails.classList.add('d-none');
                onlinePaymentOptions.classList.add('d-none');
                paymentDetails.style.display = 'none';

                paymentDetails.style.display = 'block';
                
                if (method === 'bank_transfer') {
                    bankTransferDetails.classList.remove('d-none');
                } else if (method === 'online_payment') {
                    onlinePaymentOptions.classList.remove('d-none');
                    
                    // Auto-select first gateway
                    const firstGatewayCard = document.querySelector('.gateway-card');
                    if (firstGatewayCard) {
                        firstGatewayCard.click();
                    }
                }
            }
        });
    });
    
    // Handle gateway card selection
    gatewayCards.forEach(card => {
        const radio = card.querySelector('.gateway-radio');
        
        // Click on card selects the radio
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on the radio itself
            if (e.target.type === 'radio') return;
            
            radio.checked = true;
            
            // Update card styling
            gatewayCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
        });
        
        // Click on radio also updates styling
        radio.addEventListener('change', function() {
            if (this.checked) {
                gatewayCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
            }
        });
    });
    
    // Auto-select first payment method on page load
    if (paymentCards.length > 0) {
        paymentCards[0].click();
    }

    // Handle online payment
    proceedToPaymentBtn.addEventListener('click', function() {
        const paymentGateway = document.querySelector('input[name="payment_gateway"]:checked');
        const termsChecked = document.getElementById('terms_online').checked;
        
        if (!paymentGateway) {
            alert('Please select a payment gateway (Paystack or Flutterwave).');
            return;
        }
        
        if (!termsChecked) {
            alert('Please agree to the terms and conditions.');
            return;
        }
        
        const gateway = paymentGateway.value;
        
        if (gateway === 'paystack') {
            initiatePaystackPayment();
        } else if (gateway === 'flutterwave') {
            initiateFlutterwavePayment();
        }
    });

    // Paystack Payment Function
    function initiatePaystackPayment() {
        const userEmail = '{{ Auth::user()->email }}';
        const userName = '{{ Auth::user()->name }}';
        const packagePrice = {{ $package->price ?? 0 }} * 100; // Convert to kobo
        const packageName = '{{ $package->name ?? "" }}';
        const userId = {{ Auth::user()->id }};
        const packageId = {{ $package->id ?? 0 }};
        
        // Generate unique reference
        const reference = 'HL' + Date.now() + userId;
        
        // Initialize Paystack
        const handler = PaystackPop.setup({
            key: '{{ config("services.paystack.public_key", "pk_test_xxxxxxxx") }}',
            email: userEmail,
            amount: packagePrice,
            currency: 'NGN',
            ref: reference,
            metadata: {
                custom_fields: [
                    {
                        display_name: "Customer Name",
                        variable_name: "customer_name",
                        value: userName
                    },
                    {
                        display_name: "Package",
                        variable_name: "package",
                        value: packageName
                    },
                    {
                        display_name: "User ID",
                        variable_name: "user_id",
                        value: userId
                    },
                    {
                        display_name: "Package ID",
                        variable_name: "package_id",
                        value: packageId
                    }
                ]
            },
            callback: function(response) {
                // Payment successful
                handlePaymentSuccess(response.reference, 'paystack');
            },
            onClose: function() {
                alert('Payment was cancelled.');
            }
        });
        
        handler.openIframe();
    }

    // Flutterwave Payment Function
    function initiateFlutterwavePayment() {
        const userEmail = '{{ Auth::user()->email }}';
        const userName = '{{ Auth::user()->name }}';
        const packagePrice = {{ $package->price ?? 0 }};
        const packageName = '{{ $package->name ?? "" }}';
        const userId = {{ Auth::user()->id }};
        
        // Generate unique reference
        const reference = 'HL' + Date.now() + userId;
        
        FlutterwaveCheckout({
            public_key: '{{ config("services.flutterwave.public_key", "FLWPUBK_TEST-xxxxxxxx") }}',
            tx_ref: reference,
            amount: packagePrice,
            currency: 'NGN',
            payment_options: 'card, banktransfer, ussd, mobilemoneyghana',
            customer: {
                email: userEmail,
                name: userName,
            },
            customizations: {
                title: 'Happylife Multipurpose Int\'l',
                description: `Payment for ${packageName} Package`,
                logo: '{{ asset("images/logo.png") }}',
            },
            callback: function(response) {
                // Payment successful
                handlePaymentSuccess(response.transaction_id || response.tx_ref, 'flutterwave');
            },
            onclose: function() {
                alert('Payment was cancelled.');
            }
        });
    }

    // Handle successful payment
    function handlePaymentSuccess(reference, gateway) {
        // Show loading state
        proceedToPaymentBtn.disabled = true;
        proceedToPaymentBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Activating Account...';
        
        // Send request to activate user
        fetch('{{ route("payment.activate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                reference: reference,
                gateway: gateway,
                package_id: {{ $package->id ?? 0 }},
                amount: {{ $package->price ?? 0 }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showSuccessMessage('Payment successful! Your account is now active.');
                
                // Redirect to dashboard after 2 seconds
                setTimeout(() => {
                    window.location.href = data.redirect || '{{ route("member.dashboard") }}';
                }, 2000);
            } else {
                // Activation failed
                showErrorMessage(data.message || 'Account activation failed. Please contact support.');
                proceedToPaymentBtn.disabled = false;
                proceedToPaymentBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred. Please try again or contact support.');
            proceedToPaymentBtn.disabled = false;
            proceedToPaymentBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i> Proceed to Payment Gateway';
        });
    }

    // Helper functions for messages
    function showSuccessMessage(message) {
        // Create or find alert container
        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container position-fixed top-0 start-50 translate-middle-x mt-3 z-3';
            document.body.appendChild(alertContainer);
        }
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            <i class="bi bi-check-circle-fill me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertContainer.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }

    function showErrorMessage(message) {
        // Create or find alert container
        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container position-fixed top-0 start-50 translate-middle-x mt-3 z-3';
            document.body.appendChild(alertContainer);
        }
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertContainer.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }

    // Handle bank transfer form submission
    const bankTransferForm = document.getElementById('bankTransferForm');
    if (bankTransferForm) {
        bankTransferForm.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('proof_of_payment');
            const termsChecked = document.getElementById('terms_bank').checked;
            
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Please upload proof of payment.');
                return;
            }
            
            if (!termsChecked) {
                e.preventDefault();
                alert('Please confirm that you have made the bank transfer.');
                return;
            }
            
            // Check file size (max 2MB)
            const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
            if (fileSize > 2) {
                e.preventDefault();
                alert('File size must be less than 2MB.');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Submitting...';
        });
    }
});
</script>
@endsection