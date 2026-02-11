@extends('layouts.app')

@section('title', 'Register - Happylife Multipurpose Int\'l')

@section('content')
<div class="container py-5 py-lg-6">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <!-- Registration Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-gradient text-white p-5 border-0" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
                    <div class="text-center">
                        <h2 class="h1 fw-bold mb-3 text-danger">Join Happylife Family</h2>
                        <p class="mb-0 opacity-75 text-danger">Start your journey to financial freedom today</p>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('register.post') }}" id="registerForm">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 border-bottom pb-2" style="color: #E63323;">
                                        <i class="bi bi-person-badge me-2"></i>Personal Information
                                    </h5>
                                    
                                    <!-- Full Name -->
                                    <div class="mb-4">
                                        <label for="name" class="form-label fw-semibold">Full Name *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person text-muted"></i>
                                            </span>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   required placeholder="Enter your full name">
                                        </div>
                                        @error('name') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-semibold">Email *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-envelope text-muted"></i>
                                            </span>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   required placeholder="Enter your email">
                                        </div>
                                        @error('email') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="mb-4">
                                        <label for="phone" class="form-label fw-semibold">Phone *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-phone text-muted"></i>
                                            </span>
                                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   required placeholder="Enter your phone number">
                                        </div>
                                        @error('phone') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-semibold">Password *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="password" id="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   required placeholder="Create a password">
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

                                    <!-- Confirm Password -->
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-lock-fill text-muted"></i>
                                            </span>
                                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                                   class="form-control" required placeholder="Confirm your password">
                                            <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleConfirmPassword">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Information -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 border-bottom pb-2" style="color: #E63323;">
                                        <i class="bi bi-geo-alt me-2"></i>Location Information
                                    </h5>
                                    
                                    <!-- Country -->
                                    <div class="mb-4">
                                        <label for="country" class="form-label fw-semibold">Country *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-globe text-muted"></i>
                                            </span>
                                            <select name="country" id="country" class="form-select @error('country') is-invalid @enderror" required>
                                                <option value="" selected disabled>Select your country</option>
                                                <option value="AREWA" {{ old('country') == 'AREWA' ? 'selected' : '' }}>AREWA</option>
                                                <option value="ODUDUWA" {{ old('country') == 'ODUDUWA' ? 'selected' : '' }}>ODUDUWA</option>
                                                <option value="BIAFRA" {{ old('country') == 'BIAFRA' ? 'selected' : '' }}>BIAFRA</option>
                                                <option value="OTHERS" {{ old('country') == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                                            </select>
                                        </div>
                                        @error('country') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- State -->
                                    <div class="mb-4">
                                        <label for="state" class="form-label fw-semibold">State *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-geo text-muted"></i>
                                            </span>
                                            <select name="state" id="state" class="form-select @error('state') is-invalid @enderror" required>
                                                <option value="" selected disabled>Select your state</option>
                                                @if(old('country') == 'AREWA')
                                                    <option value="Kano Central" {{ old('state') == 'Kano Central' ? 'selected' : '' }}>Kano Central</option>
                                                    <option value="Nasarawa Center" {{ old('state') == 'Nasarawa Center' ? 'selected' : '' }}>Nasarawa Center</option>
                                                @elseif(old('country') == 'ODUDUWA')
                                                    <option value="Lagos Mainland" {{ old('state') == 'Lagos Mainland' ? 'selected' : '' }}>Lagos Mainland</option>
                                                    <option value="Ibadan Central" {{ old('state') == 'Ibadan Central' ? 'selected' : '' }}>Ibadan Central</option>
                                                @elseif(old('country') == 'BIAFRA')
                                                    <option value="Port Harcourt Main" {{ old('state') == 'Port Harcourt Main' ? 'selected' : '' }}>Port Harcourt Main</option>
                                                    <option value="Enugu North" {{ old('state') == 'Enugu North' ? 'selected' : '' }}>Enugu North</option>
                                                @endif
                                            </select>
                                        </div>
                                        @error('state') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Pickup Center -->
                                    <div class="mb-4">
                                        <label for="pickup_center" class="form-label fw-semibold">Pickup Center *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-shop text-muted"></i>
                                            </span>
                                            <select name="pickup_center" id="pickup_center" class="form-select @error('pickup_center') is-invalid @enderror" required>
                                                <option value="" selected disabled>Select pickup center</option>
                                            </select>
                                        </div>
                                        @error('pickup_center') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <!-- Referral Information -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 border-bottom pb-2" style="color: #E63323;">
                                        <i class="bi bi-people me-2"></i>Referral Information
                                    </h5>
                                    
                                    <!-- Sponsor Code -->
                                    <div class="mb-4">
                                        <label for="sponsor_code" class="form-label fw-semibold">Sponsor Referral Code *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person-plus text-muted"></i>
                                            </span>
                                            <input type="text" name="sponsor_code" id="sponsor_code" value="{{ old('sponsor_code') }}" 
                                                   class="form-control @error('sponsor_code') is-invalid @enderror" 
                                                   required placeholder="Enter sponsor's referral code">
                                        </div>
                                        <small class="text-muted">Enter your sponsor's referral code</small>
                                        <div id="sponsor_info" class="mt-2 alert alert-success" style="display:none;">
                                            <i class="bi bi-check-circle me-2"></i>Valid sponsor: <span id="sponsor_name"></span>
                                        </div>
                                        @error('sponsor_code') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Placement Code -->
                                    <div class="mb-4">
                                        <label for="placement_code" class="form-label fw-semibold">Placement Referral Code *</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-diagram-3 text-muted"></i>
                                            </span>
                                            <input type="text" name="placement_code" id="placement_code" value="{{ old('placement_code') }}" 
                                                   class="form-control @error('placement_code') is-invalid @enderror" 
                                                   required placeholder="Enter placement referral code">
                                        </div>
                                        <small class="text-muted">Enter the referral code of where you want to be placed</small>
                                        <div id="placement_info" class="mt-2 alert alert-success" style="display:none;">
                                            <i class="bi bi-check-circle me-2"></i>Valid placement: <span id="placement_name"></span>
                                        </div>
                                        @error('placement_code') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Placement Position -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Placement Position *</label>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check form-check-inline me-4">
                                                <input class="form-check-input" type="radio" name="placement_position" id="left" value="left" required {{ old('placement_position') == 'left' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="left">
                                                    <i class="bi bi-arrow-left-circle fs-4 me-2"></i>Left
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="placement_position" id="right" value="right" required {{ old('placement_position') == 'right' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="right">
                                                    <i class="bi bi-arrow-right-circle fs-4 me-2"></i>Right
                                                </label>
                                            </div>
                                        </div>
                                        @error('placement_position') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Package Selection (converted to select) -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 border-bottom pb-2" style="color: #E63323;">
                                        <i class="bi bi-box-seam me-2"></i>Select Your Package
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="package_id" class="form-label fw-semibold visually-hidden">Package</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-card-list text-muted"></i>
                                            </span>
                                            <select name="package_id" id="package_id" class="form-select @error('package_id') is-invalid @enderror" required>
                                                <option value="" selected disabled>Select a package</option>
                                                @foreach($packages as $pkg)
                                                    <option value="{{ $pkg->id }}" {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                                        {{ $pkg->name }} — ₦{{ number_format($pkg->price, 0) }} ({{ $pkg->pv }} PV)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('package_id') 
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Package preview card (keeps visual feel of package cards) -->
                                    <div id="package_preview" class="card border-light d-none">
                                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 id="preview_name" class="mb-1 fw-bold text-danger"></h6>
                                                <div id="preview_price" class="display-6 fw-bold text-dark fs-5"></div>
                                                <div id="preview_pv" class="mb-1"><span class="badge bg-teal text-white"></span></div>
                                                <p id="preview_desc" class="small text-muted mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Selection -->
                                <div class="mb-4">
                                    <label for="product_id" class="form-label fw-semibold">Select Product *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-gift text-muted"></i>
                                        </span>
                                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" disabled required>
                                            <option value="" selected disabled>Select a product</option>
                                        </select>
                                    </div>
                                    @error('product_id') 
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Terms & Conditions -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" 
                                               name="terms" id="terms" required {{ old('terms') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" class="text-danger fw-semibold">Terms & Conditions</a> and 
                                            <a href="#" class="text-danger fw-semibold">Privacy Policy</a> *
                                        </label>
                                    </div>
                                    @error('terms') 
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-danger btn-lg py-3 fw-bold">
                                <i class="bi bi-person-plus me-2"></i> Create Account
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Already have an account? 
                                <a href="{{ route('login') }}" class="text-danger fw-semibold text-decoration-none">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
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
                    <small class="text-muted">Your information is protected with bank-level security & encryption</small>
                </div>
            </div>
        </div>
    </div>
</div>

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
    
    .package-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    
    .package-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #E63323 !important;
    }
    
    .package-card.selected {
        border-color: #E63323 !important;
        background-color: rgba(230, 51, 35, 0.05);
        box-shadow: 0 8px 16px rgba(230, 51, 35, 0.1);
    }
    
    .form-check-input:checked {
        background-color: #E63323;
        border-color: #E63323;
    }
    
    @media (max-width: 768px) {
        .py-lg-6 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
        
        .card-header, .card-body {
            padding: 2rem !important;
        }
        
        .package-card {
            margin-bottom: 1rem;
        }
    }
</style>

@php
    // Prepare packages data for safe injection into JS (avoid closures inside @json)
    $packagesData = $packages->map(function($p){
        return [
            'id' => $p->id,
            'name' => $p->name,
            'price' => $p->price,
            'pv' => $p->pv ?? null,
            'description' => $p->description ?? null
        ];
    })->keyBy('id');
@endphp

<script>
document.addEventListener("DOMContentLoaded", function(){
    const statesData = {
        'AREWA': ['Kano Central', 'Nasarawa Center'],
        'ODUDUWA': ['Lagos Mainland', 'Ibadan Central'],
        'BIAFRA': ['Port Harcourt Main', 'Enugu North']
    };

    const pickupCentersData = {
        'Kano Central': ['Kano Shop 1','Kano Shop 2'],
        'Nasarawa Center': ['Nasarawa Shop 1'],
        'Lagos Mainland': ['Lagos Shop'],
        'Ibadan Central': ['Ibadan Shop'],
        'Port Harcourt Main': ['PH Shop A'],
        'Enugu North': ['Enugu Shop B']
    };

    // productsData is expected from backend: { packageId: [ {id,name}, ... ], ... }
    const productsData = @json($productsJs ?? []);
    // packagesData to show preview (optional fields: id, name, price, pv, description)
    const packagesData = @json($packagesData);

    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');
    const pickupSelect = document.getElementById('pickup_center');
    const productSelect = document.getElementById('product_id');
    const packageSelect = document.getElementById('package_id');
    const packagePreview = document.getElementById('package_preview');
    const previewName = document.getElementById('preview_name');
    const previewPrice = document.getElementById('preview_price');
    const previewPV = document.getElementById('preview_pv');
    const previewDesc = document.getElementById('preview_desc');

    const sponsorCodeInput = document.getElementById('sponsor_code');
    const placementCodeInput = document.getElementById('placement_code');
    const sponsorInfo = document.getElementById('sponsor_info');
    const placementInfo = document.getElementById('placement_info');

    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('password_confirmation');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
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

    if (toggleConfirmPassword && confirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            
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

    // Country change
    countrySelect.addEventListener('change', function(){
        const country = this.value;
        stateSelect.innerHTML = '<option value="" disabled selected>Select state</option>';
        pickupSelect.innerHTML = '<option value="" disabled selected>Select pickup center</option>';
        productSelect.disabled = true;
        
        if(statesData[country]){
            statesData[country].forEach(s => {
                const opt = document.createElement('option');
                opt.value = s;
                opt.textContent = s;
                stateSelect.appendChild(opt);
            });
        }
    });

    // State change
    stateSelect.addEventListener('change', function(){
        const state = this.value;
        pickupSelect.innerHTML = '<option value="" disabled selected>Select pickup center</option>';
        
        if(pickupCentersData[state]){
            pickupCentersData[state].forEach(c => {
                const opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                pickupSelect.appendChild(opt);
            });
        }
    });

    // Package select change
    function handlePackageChange(packageId, restoreProductId = null) {
        // preview
        if (packagesData && packagesData[packageId]) {
            const p = packagesData[packageId];
            previewName.textContent = p.name;
            previewPrice.textContent = p.price ? `₦${Number(p.price).toLocaleString()}` : '';
            previewPV.innerHTML = `<span class="badge bg-teal text-white">${p.pv ? p.pv + ' PV' : ''}</span>`;
            previewDesc.textContent = p.description ?? '';
            packagePreview.classList.remove('d-none');
        } else {
            packagePreview.classList.add('d-none');
        }

        // products
        productSelect.innerHTML = '<option value="" disabled selected>Select a product</option>';
        productSelect.disabled = true;
        if (productsData && productsData[packageId]) {
            productsData[packageId].forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.name;
                productSelect.appendChild(opt);
            });
            productSelect.disabled = false;

            // restore old product selection if provided
            if (restoreProductId) {
                const optionToSelect = Array.from(productSelect.options).find(o => o.value == restoreProductId);
                if (optionToSelect) {
                    optionToSelect.selected = true;
                }
            }
        }
    }

    packageSelect.addEventListener('change', function(){
        const packageId = this.value;
        handlePackageChange(packageId);
    });

    // On page load: if old package selected restore preview & products
    const oldPackage = packageSelect.value;
    const oldProduct = "{{ old('product_id') }}";
    if (oldPackage) {
        handlePackageChange(oldPackage, oldProduct || null);
    }

    // Check referral code validity
    function checkReferralCode(code, type) {
        if (code.length >= 2) {
            fetch(`/check-referral/${code}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        if (type === 'sponsor') {
                            sponsorInfo.style.display = 'block';
                            document.getElementById('sponsor_name').textContent = data.name;
                        } else {
                            placementInfo.style.display = 'block';
                            document.getElementById('placement_name').textContent = data.name;
                        }
                    } else {
                        if (type === 'sponsor') {
                            sponsorInfo.style.display = 'none';
                        } else {
                            placementInfo.style.display = 'none';
                        }
                    }
                })
                .catch(() => {
                    // silently fail - hide info on error
                    if (type === 'sponsor') {
                        sponsorInfo.style.display = 'none';
                    } else {
                        placementInfo.style.display = 'none';
                    }
                });
        } else {
            if (type === 'sponsor') {
                sponsorInfo.style.display = 'none';
            } else {
                placementInfo.style.display = 'none';
            }
        }
    }

    // Sponsor code validation
    sponsorCodeInput.addEventListener('input', function() {
        checkReferralCode(this.value, 'sponsor');
    });

    // Placement code validation
    placementCodeInput.addEventListener('input', function() {
        checkReferralCode(this.value, 'placement');
    });

    // Check referral codes on page load if they exist
    if (sponsorCodeInput.value) {
        checkReferralCode(sponsorCodeInput.value, 'sponsor');
    }
    if (placementCodeInput.value) {
        checkReferralCode(placementCodeInput.value, 'placement');
    }
});
</script>
@endsection
