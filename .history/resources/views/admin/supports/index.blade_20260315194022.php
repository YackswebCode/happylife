@extends('layouts.admin')

@section('title', 'Support Topics')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Support Topics</h2>
        <a href="{{ route('admin.supports.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New
        </a>
    </div>

    <!-- Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.supports.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by title, content, phone, email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Support Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Last Updated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supports as $support)
                    <tr>
                        <td>#{{ $support->id }}</td>
                        <td>{{ $support->title }}</td>
                        <td>{{ Str::limit(strip_tags($support->content), 50) }}</td>
                        <td>{{ $support->phone ?? '—' }}</td>
                        <td>{{ $support->email ?? '—' }}</td>
                        <td>{{ $support->updated_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.supports.show', $support) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.supports.edit', $support) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.supports.destroy', $support) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this support entry?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No support entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($supports->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $supports->links() }}
        </div>
        @endif
    </div>
</div>
@endsection