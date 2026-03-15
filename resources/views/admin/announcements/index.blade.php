@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Announcements</h2>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.announcements.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by title or content..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Announcements Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                        <tr>
                            <td>#{{ $announcement->id }}</td>
                            <td>{{ $announcement->title }}</td>
                            <td>{{ Str::limit(strip_tags($announcement->content), 50) }}</td>
                            <td>
                                @if($announcement->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $announcement->created_at->format('d M Y') }}</td>
                            <td>{{ $announcement->updated_at->diffForHumans() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this announcement?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No announcements found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($announcements->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection