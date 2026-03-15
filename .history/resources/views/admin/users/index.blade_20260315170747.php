@extends('layouts.admin')

@section('title', 'Manage Users - Admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Manage Users</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New User
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Name, email, username...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="package" class="form-label">Package</label>
                        <select class="form-select" id="package" name="package">
                            <option value="">All</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" {{ request('package') == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-red w-100">
                            <i class="bi bi-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 32px; height: 32px; background: var(--color-teal-blue);">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>{{ $user->name }}</div>
                                </div>
                            </td>
                            <td><code>{{ $user->username }}</code></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td>{{ $user->package->name ?? '—' }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'active' => 'success',
                                        'inactive' => 'secondary',
                                        'suspended' => 'danger',
                                    ][$user->status] ?? 'warning';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($user->status) }}</span>
                            </td>
                            <td>
                                @if($user->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection