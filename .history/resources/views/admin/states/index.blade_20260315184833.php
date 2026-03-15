@extends('layouts.admin')

@section('title', 'Manage States')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">States</h2>
        <a href="{{ route('admin.states.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New State
        </a>
    </div>

    <!-- Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.states.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or code..." value="{{ request('search') }}">
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
                        <th>Name</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Pickup Centers</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($states as $state)
                    <tr>
                        <td>#{{ $state->id }}</td>
                        <td>{{ $state->name }}</td>
                        <td><code>{{ $state->code }}</code></td>
                        <td>
                            @if($state->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $state->pickupCenters()->count() }}</td>
                        <td>{{ $state->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.states.show', $state) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.states.edit', $state) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.states.destroy', $state) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this state? This will affect pickup centers.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No states found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($states->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $states->links() }}
        </div>
        @endif
    </div>
</div>
@endsection