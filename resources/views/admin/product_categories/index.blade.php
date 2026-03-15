@extends('layouts.admin')

@section('title', 'Product Categories')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Product Categories</h2>
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New Category
        </a>
    </div>

    <!-- Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.product-categories.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>#{{ $category->id }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.product-categories.show', $category) }}" class="btn btn-sm btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.product-categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.product-categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category? Products under it will be orphaned, so ensure none exist.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection