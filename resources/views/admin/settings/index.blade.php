@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Site Settings</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">General Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="site_name" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="logo" class="form-label">Logo</label>
                            @if(!empty($settings['logo']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$settings['logo']) }}" alt="Logo" style="max-height: 60px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <small class="text-muted">Recommended size: 200x50px. Leave blank to keep current.</small>
                        </div>

                        <div class="mb-4">
                            <label for="favicon" class="form-label">Favicon</label>
                            @if(!empty($settings['favicon']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="Favicon" style="max-height: 32px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="favicon" name="favicon" accept=".ico,.png">
                            <small class="text-muted">Recommended format: .ico or .png, 32x32px.</small>
                        </div>

                        <button type="submit" class="btn btn-red px-4 py-2">
                            <i class="bi bi-save me-2"></i>Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection