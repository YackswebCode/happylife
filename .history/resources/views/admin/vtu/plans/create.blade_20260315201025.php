@extends('layouts.admin')

@section('title', 'Add VTU Plan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Add New Plan</h2>
        <a href="{{ route('admin.vtu.plans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <form action="{{ route('admin.vtu.plans.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="provider_id" class="form-label">Provider <span class="text-danger">*</span></label>
                            <select class="form-select @error('provider_id') is-invalid @enderror" id="provider_id" name="provider_id" required>
                                <option value="">Select Provider</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>{{ $provider->name }} ({{ $provider->category }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="api_code" class="form-label">API Code (optional)</label>
                            <input type="text" class="form-control" id="api_code" name="api_code" value="{{ old('api_code') }}">
                            <small class="text-muted">Code used by the API provider</small>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₦) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="size" class="form-label">Size (e.g., 1GB, 500MB)</label>
                                    <input type="text" class="form-control" id="size" name="size" value="{{ old('size') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validity" class="form-label">Validity (e.g., 30 days)</label>
                                    <input type="text" class="form-control" id="validity" name="validity" value="{{ old('validity') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-red mt-3 px-4 py-2">Create Plan</button>
    </form>
</div>
@endsection