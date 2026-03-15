@extends('layouts.admin')

@section('title', 'Provider Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">{{ $provider->name }}</h2>
        <div>
            <a href="{{ route('admin.vtu.providers.edit', $provider) }}" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Edit</a>
            <a href="{{ route('admin.vtu.providers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>Name:</th><td>{{ $provider->name }}</td></tr>
                        <tr><th>Category:</th><td>{{ ucfirst($provider->category) }}</td></tr>
                        <tr><th>Code:</th><td><code>{{ $provider->code }}</code></td></tr>
                        <tr><th>Status:</th><td>{!! $provider->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' !!}</td></tr>
                        <tr><th>Created:</th><td>{{ $provider->created_at->format('d M Y, H:i') }}</td></tr>
                        <tr><th>Last Updated:</th><td>{{ $provider->updated_at->format('d M Y, H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        @if($provider->logo)
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ asset('storage/'.$provider->logo) }}" alt="{{ $provider->name }}" class="img-fluid" style="max-height: 150px;">
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection