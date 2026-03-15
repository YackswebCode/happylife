@extends('layouts.admin')

@section('title', 'Add VTU Provider')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Add New Provider</h2>
        <a href="{{ route('admin.vtu.providers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <form action="{{ route('admin.vtu.providers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Provider Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="airtime" {{ old('category') == 'airtime' ? 'selected' : '' }}>Airtime</option>
                                <option value="data" {{ old('category') == 'data' ? 'selected' : '' }}>Data</option>
                                <option value="cable" {{ old('category') == 'cable' ? 'selected' : '' }}>Cable TV</option>
                                <option value="electricity" {{ old('category') == 'electricity' ? 'selected' : '' }}>Electricity</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Provider Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                            <small class="text-muted">Unique identifier used in API calls (e.g., MTN, GLO)</small>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo (optional)</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
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
        <button type="submit" class="btn btn-red mt-3 px-4 py-2">Create Provider</button>
    </form>
</div>
@endsection