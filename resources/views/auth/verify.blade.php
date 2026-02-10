@extends('layouts.app')

@section('title', 'Verify Email - Happylife Multipurpose Int\'l')

@section('content')
<div class="container py-5 py-lg-6">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Verification Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-gradient text-white p-5 border-0" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
                    <div class="text-center">
                        <h2 class="h1 fw-bold mb-3">Verify Your Email</h2>
                        <p class="mb-0 opacity-75">Complete your registration process</p>
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
                    
                    <!-- Verification Info -->
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <i class="bi bi-envelope-check text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Verification Code Sent</h5>
                        <p class="text-muted">
                            We've sent a 6-digit verification code to 
                            <strong>{{ $user->email ?? 'your email' }}</strong>. 
                            Enter it below to verify your email address.
                        </p>
                    </div>

                    <!-- Verification Form -->
                    <form method="POST" action="{{ route('verification.verify') }}">
                        @csrf
                        
                        <!-- Code Input -->
                        <div class="mb-4">
                            <label for="code" class="form-label fw-semibold">Verification Code *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-shield-check text-muted"></i>
                                </span>
                                <input type="text" id="code" name="code" 
                                       class="form-control text-center fs-3 @error('code') is-invalid @enderror" 
                                       required maxlength="6" pattern="\d{6}" 
                                       placeholder="0 0 0 0 0 0" autofocus>
                            </div>
                            @error('code') 
                                <div class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                            <small class="text-muted mt-2 d-block text-center">
                                Enter the 6-digit code from your email
                            </small>
                        </div>

                        <!-- Code Digits -->
                        <div class="row mb-4" id="codeDigits">
                            @for($i = 1; $i <= 6; $i++)
                                <div class="col-2">
                                    <input type="text" maxlength="1" class="form-control text-center fs-3 code-digit" 
                                           data-index="{{ $i-1 }}">
                                </div>
                            @endfor
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-danger btn-lg py-3 fw-bold">
                                <i class="bi bi-check-circle me-2"></i> Verify Email
                            </button>
                            
                            <!-- Resend Code -->
                            <form method="POST" action="{{ route('verification.resend') }}" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg py-3 fw-bold">
                                    <i class="bi bi-arrow-clockwise me-2"></i> Resend Code
                                </button>
                            </form>
                            
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg py-3">
                                <i class="bi bi-arrow-left me-2"></i> Back to Register
                            </a>
                        </div>
                    </form>

                    <!-- Help Text -->
                    <div class="alert alert-light border mt-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle text-danger me-3 fs-4"></i>
                            <div>
                                <small class="text-muted">
                                    <strong>Didn't receive the code?</strong><br>
                                    • Check your spam folder<br>
                                    • Make sure you entered the correct email<br>
                                    • Wait a few minutes and try again
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Note -->
            <div class="alert alert-light border mt-4 text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-shield-check text-success me-2"></i>
                    <small class="text-muted">This verification code expires in 24 hours for security</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
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
    
    .btn-outline-danger:hover {
        background-color: #E63323;
        color: white;
    }
    
    .code-digit {
        height: 60px;
        font-weight: bold;
        color: #E63323;
    }
    
    .code-digit:focus {
        border-color: #E63323;
        box-shadow: 0 0 0 0.25rem rgba(230, 51, 35, 0.25);
    }
    
    @media (max-width: 768px) {
        .py-lg-6 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
        
        .card-header, .card-body {
            padding: 2rem !important;
        }
        
        .code-digit {
            height: 50px;
            font-size: 1.5rem !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const codeDigits = document.querySelectorAll('.code-digit');
    
    // Focus on first digit
    if (codeDigits.length > 0) {
        codeDigits[0].focus();
    }
    
    // Handle digit input
    codeDigits.forEach((digit, index) => {
        digit.addEventListener('input', function(e) {
            // Allow only numbers
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto move to next digit
            if (this.value.length === 1 && index < codeDigits.length - 1) {
                codeDigits[index + 1].focus();
            }
            
            // Update hidden input
            updateCodeInput();
        });
        
        digit.addEventListener('keydown', function(e) {
            // Handle backspace
            if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                codeDigits[index - 1].focus();
            }
        });
        
        digit.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = paste.replace(/[^0-9]/g, '').split('');
            
            numbers.forEach((num, numIndex) => {
                if (index + numIndex < codeDigits.length) {
                    codeDigits[index + numIndex].value = num;
                }
            });
            
            // Focus on last filled digit or submit
            const lastIndex = Math.min(index + numbers.length - 1, codeDigits.length - 1);
            codeDigits[lastIndex].focus();
            updateCodeInput();
        });
    });
    
    function updateCodeInput() {
        let code = '';
        codeDigits.forEach(digit => {
            code += digit.value;
        });
        codeInput.value = code;
    }
    
    // Auto-submit when all digits are filled
    codeDigits.forEach(digit => {
        digit.addEventListener('input', function() {
            let allFilled = true;
            codeDigits.forEach(d => {
                if (!d.value) allFilled = false;
            });
            
            if (allFilled) {
                setTimeout(() => {
                    document.querySelector('form').submit();
                }, 500);
            }
        });
    });
    
    // Allow only numbers in main input
    codeInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Update digit boxes
        const digits = this.value.split('');
        codeDigits.forEach((digit, index) => {
            digit.value = digits[index] || '';
        });
    });
});
</script>
@endsection