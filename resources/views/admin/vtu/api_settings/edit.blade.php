@extends('layouts.admin')

@section('title', 'Edit API Setting')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Edit API Setting</h2>
        <a href="{{ route('admin.vtu.api-settings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <form action="{{ route('admin.vtu.api-settings.update', $apiSetting) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Provider Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $apiSetting->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="base_url" class="form-label">Base URL <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('base_url') is-invalid @enderror" id="base_url" name="base_url" value="{{ old('base_url', $apiSetting->base_url) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="api_key" class="form-label">API Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('api_key') is-invalid @enderror" id="api_key" name="api_key" value="{{ old('api_key', $apiSetting->api_key) }}" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $apiSetting->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Set as active (only one can be active)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-red mt-3 px-4 py-2">Update Setting</button>
    </form>
</div>
@endsection