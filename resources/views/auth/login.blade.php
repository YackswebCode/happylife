@extends('layouts.app')

@section('title', 'Login - Happylife Multipurpose Int\'l')

@section('content')
<div class="container py-5 py-lg-6">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Login Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-gradient text-white p-5 border-0" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
                    <div class="text-center">
                        <h2 class="h1 fw-bold mb-3 text-danger">Welcome Back!</h2>
                        <p class="mb-0 opacity-75 text-danger">Sign in to your Happylife account</p>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address or Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                       placeholder="Enter your email or username">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="current-password"
                                       placeholder="Enter your password">
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">
                                    Remember me
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-danger text-decoration-none fw-semibold" href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-4">
                            <button type="submit" class="btn btn-danger btn-lg w-100 py-3 fw-bold">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                            </button>
                        </div>


                        <!-- Registration Link -->
                        <div class="text-center">
                            <p class="text-muted mb-0">Don't have an account? 
                                <a href="{{ route('register') }}" class="text-danger fw-semibold text-decoration-none">
                                    Create Account
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Note -->
            <div class="alert alert-light border mt-4 text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-shield-check text-success me-2"></i>
                    <small class="text-muted">Your data is protected with bank-level security & encryption</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-icon svg {
        transition: transform 0.3s ease;
    }
    
    .login-icon:hover svg {
        transform: scale(1.1);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #E63323;
        box-shadow: 0 0 0 0.25rem rgba(230, 51, 35, 0.25);
    }
    
    .btn-danger {
        background-color: #E63323;
        border-color: #E63323;
    }
    
    .btn-danger:hover {
        background-color: #d6281a;
        border-color: #d6281a;
    }
    
    @media (max-width: 768px) {
        .py-lg-6 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }
        
        .card-header, .card-body {
            padding: 2rem !important;
        }
    }
</style>

<script>
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle eye icon
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                
                if (!email.value || !password.value) {
                    e.preventDefault();
                    if (!email.value) {
                        email.classList.add('is-invalid');
                    }
                    if (!password.value) {
                        password.classList.add('is-invalid');
                    }
                }
            });
        }
    });
</script>
@endsection