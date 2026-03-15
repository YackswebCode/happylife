@extends('layouts.admin')

@section('title', 'Create New User - Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Create New User</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Users
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Basic Info -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            <small class="text-muted">Username will be auto-generated from this name (format: happylif_name).</small>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">— Select —</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account & Package -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Account Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="payment_status" name="payment_status">
                                <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="package_id" class="form-label">Package</label>
                            <select class="form-select" id="package_id" name="package_id">
                                <option value="">— None —</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}" {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                        {{ $pkg->name }} (₦{{ number_format($pkg->price) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rank_id" class="form-label">Rank</label>
                            <select class="form-select" id="rank_id" name="rank_id">
                                <option value="">— None —</option>
                                @foreach($ranks as $rank)
                                    <option value="{{ $rank->id }}" {{ old('rank_id') == $rank->id ? 'selected' : '' }}>
                                        {{ $rank->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet Balances (Optional) -->
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Wallet Balances (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="commission_wallet_balance" class="form-label">Commission Wallet</label>
                                <input type="number" step="0.01" class="form-control" id="commission_wallet_balance" name="commission_wallet_balance" value="{{ old('commission_wallet_balance', 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="registration_wallet_balance" class="form-label">Registration Wallet</label>
                                <input type="number" step="0.01" class="form-control" id="registration_wallet_balance" name="registration_wallet_balance" value="{{ old('registration_wallet_balance', 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="shopping_wallet_balance" class="form-label">Shopping Wallet</label>
                                <input type="number" step="0.01" class="form-control" id="shopping_wallet_balance" name="shopping_wallet_balance" value="{{ old('shopping_wallet_balance', 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="rank_wallet_balance" class="form-label">Rank Wallet</label>
                                <input type="number" step="0.01" class="form-control" id="rank_wallet_balance" name="rank_wallet_balance" value="{{ old('rank_wallet_balance', 0) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="col-12">
                <button type="submit" class="btn btn-red px-4 py-2">
                    <i class="bi bi-person-plus me-2"></i>Create User
                </button>
            </div>
        </div>
    </form>
</div>
@endsection