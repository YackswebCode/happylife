@extends('layouts.admin')

@section('title', 'Repurchase Products')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Repurchase Products</h2>
        <a href="{{ route('admin.repurchase-products.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.repurchase-products.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or SKU..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>PV</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>#{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td><code>{{ $product->sku }}</code></td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>₦{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->pv_value }}</td>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Out of stock</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.repurchase-products.show', $product) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.repurchase-products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.repurchase-products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-4 text-muted">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection