@extends('layouts.admin')

@section('title', 'Pickup Centers')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Pickup Centers</h2>
        <a href="{{ route('admin.pickup-centers.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pickup-centers.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, address, contact..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="state_id" class="form-select">
                            <option value="">All States</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
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
                        <th>Name</th>
                        <th>State</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($centers as $center)
                    <tr>
                        <td>#{{ $center->id }}</td>
                        <td>{{ $center->name }}</td>
                        <td>{{ $center->state->name ?? '—' }}</td>
                        <td>{{ $center->contact_person }}</td>
                        <td>{{ $center->contact_phone }}</td>
                        <td>
                            @if($center->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.pickup-centers.show', $center) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.pickup-centers.edit', $center) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.pickup-centers.destroy', $center) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this pickup center?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No pickup centers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($centers->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $centers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection