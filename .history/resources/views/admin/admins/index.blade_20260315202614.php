@extends('layouts.admin')

@section('title', 'Manage Admins')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Admins</h2>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add Admin
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.admins.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
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
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>#{{ $admin->id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if($admin->role == 'super_admin')
                                <span class="badge bg-danger">Super Admin</span>
                            @else
                                <span class="badge bg-info">Admin</span>
                            @endif
                        </td>
                        <td>{{ $admin->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this admin?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No admins found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admins->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $admins->links() }}
        </div>
        @endif
    </div>
</div>
@endsection