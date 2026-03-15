@extends('layouts.admin')

@section('title', 'VTU Providers')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">VTU Providers</h2>
        <a href="{{ route('admin.vtu.providers.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add Provider
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vtu.providers.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search name or code..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <option value="airtime" {{ request('category') == 'airtime' ? 'selected' : '' }}>Airtime</option>
                            <option value="data" {{ request('category') == 'data' ? 'selected' : '' }}>Data</option>
                            <option value="cable" {{ request('category') == 'cable' ? 'selected' : '' }}>Cable TV</option>
                            <option value="electricity" {{ request('category') == 'electricity' ? 'selected' : '' }}>Electricity</option>
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
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Code</th>
                        <th>Plans</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($providers as $provider)
                    <tr>
                        <td>#{{ $provider->id }}</td>
                        <td>
                            @if($provider->logo)
                                <img src="{{ asset('storage/'.$provider->logo) }}" alt="{{ $provider->name }}" style="width: 40px; height: 40px; object-fit: contain;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $provider->name }}</td>
                        <td>{{ ucfirst($provider->category) }}</td>
                        <td><code>{{ $provider->code }}</code></td>
                        <td>{{ $provider->plans()->count() }}</td>
                        <td>
                            @if($provider->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.vtu.providers.show', $provider) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.vtu.providers.edit', $provider) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.vtu.providers.destroy', $provider) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this provider?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No providers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($providers->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $providers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection