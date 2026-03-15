@extends('layouts.admin')

@section('title', 'VTU Plans')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">VTU Plans</h2>
        <a href="{{ route('admin.vtu.plans.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add Plan
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vtu.plans.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="provider_id" class="form-select">
                            <option value="">All Providers</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{ request('provider_id') == $provider->id ? 'selected' : '' }}>{{ $provider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Provider</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Size</th>
                        <th>Validity</th>
                        <th>API Code</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                    <tr>
                        <td>#{{ $plan->id }}</td>
                        <td>{{ $plan->provider->name ?? '—' }}</td>
                        <td>{{ $plan->name }}</td>
                        <td>₦{{ number_format($plan->amount, 2) }}</td>
                        <td>{{ $plan->size ?? '—' }}</td>
                        <td>{{ $plan->validity ?? '—' }}</td>
                        <td><code>{{ $plan->api_code ?? '—' }}</code></td>
                        <td>
                            @if($plan->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.vtu.plans.show', $plan) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.vtu.plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.vtu.plans.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this plan?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No plans found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($plans->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $plans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection