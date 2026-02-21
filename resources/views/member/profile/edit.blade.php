@extends('layouts.member')

@section('title', 'Edit Profile - Happylife Multipurpose Int\'l')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-dark-gray">Edit Profile</h1>
                <p class="text-muted mb-0">Update your personal information and password.</p>
            </div>
            <div>
                <a href="{{ route('member.profile.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the errors below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header-custom rounded-top">
                <h5 class="mb-0 text-white"><i class="bi bi-pencil-square me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('member.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Your email must be verified to make changes to sensitive account settings.</small>
                    </div>
                    
                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Country & State (dropdowns) -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label fw-semibold">Country</label>
                            <select class="form-select @error('country') is-invalid @enderror" 
                                    id="country" name="country">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" 
                                        {{ old('country', $selectedCountryId) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label fw-semibold">State</label>
                            <select class="form-select @error('state') is-invalid @enderror" 
                                    id="state" name="state">
                                <option value="">Select State</option>
                                <!-- Options will be populated via JavaScript -->
                            </select>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Password Change Section -->
                    <h5 class="text-dark-gray mb-3"><i class="bi bi-shield-lock-fill text-teal-blue me-2"></i>Change Password</h5>
                    <p class="text-muted small">Leave blank if you don't want to change your password.</p>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold">New Password</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" name="new_password">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" class="form-control" 
                               id="new_password_confirmation" name="new_password_confirmation">
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-red px-5">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password confirmation validation
    document.querySelector('form').addEventListener('submit', function(e) {
        let newPass = document.getElementById('new_password');
        let confirmPass = document.getElementById('new_password_confirmation');
        if (newPass.value !== confirmPass.value) {
            e.preventDefault();
            confirmPass.classList.add('is-invalid');
            let feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.innerText = 'Password confirmation does not match.';
            confirmPass.parentNode.appendChild(feedback);
        }
    });

    // Dynamic state dropdown based on country selection
    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');
    const oldStateId = '{{ old('state') }}';
    const storedStateName = '{{ $user->state ?? '' }}';
    const selectedCountryId = countrySelect.value; // already set from old or DB

    function loadStates(countryId, callback) {
        // Clear current options
        stateSelect.innerHTML = '<option value="">Select State</option>';
        if (!countryId) {
            if (callback) callback();
            return;
        }

        fetch(`/member/profile/states/${countryId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    stateSelect.appendChild(option);
                });
                if (callback) callback();
            })
            .catch(error => console.error('Error loading states:', error));
    }

    // After loading states, try to pre-select based on old input or stored state name
    function preSelectState() {
        if (oldStateId) {
            // If there's an old input (after validation error), select that ID
            for (let i = 0; i < stateSelect.options.length; i++) {
                if (stateSelect.options[i].value == oldStateId) {
                    stateSelect.options[i].selected = true;
                    break;
                }
            }
        } else if (storedStateName) {
            // Otherwise, try to match the stored state name
            for (let i = 0; i < stateSelect.options.length; i++) {
                if (stateSelect.options[i].text === storedStateName) {
                    stateSelect.options[i].selected = true;
                    break;
                }
            }
        }
    }

    // On page load, if a country is selected, load its states and then pre-select
    if (selectedCountryId) {
        loadStates(selectedCountryId, preSelectState);
    }

    // When country changes, load states (and clear selection)
    countrySelect.addEventListener('change', function() {
        loadStates(this.value);
    });
</script>
@endpush