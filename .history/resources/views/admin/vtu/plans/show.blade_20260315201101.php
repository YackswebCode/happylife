@extends('layouts.admin')

@section('title', 'Plan Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">{{ $plan->name }}</h2>
        <div>
            <a href="{{ route('admin.vtu.plans.edit', $plan) }}" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Edit</a>
            <a href="{{ route('admin.vtu.plans.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>Provider:</th><td>{{ $plan->provider->name ?? '—' }} ({{ $plan->provider->category ?? '' }})</td></tr>
                        <tr><th>Name:</th><td>{{ $plan->name }}</td></tr>
                        <tr><th>Amount:</th><td>₦{{ number_format($plan->amount, 2) }}</td></tr>
                        <tr><th>Size:</th><td>{{ $plan->size ?? '—' }}</td></tr>
                        <tr><th>Validity:</th><td>{{ $plan->validity ?? '—' }}</td></tr>
                        <tr><th>API Code:</th><td><code>{{ $plan->api_code ?? '—' }}</code></td></tr>
                        <tr><th>Status:</th><td>{!! $plan->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' !!}</td></tr>
                        <tr><th>Created:</th><td>{{ $plan->created_at->format('d M Y, H:i') }}</td></tr>
                        <tr><th>Last Updated:</th><td>{{ $plan->updated_at->format('d M Y, H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection