@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Products</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}">
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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Package</th>
                        <th>Price</th>
                        <th>PV</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>#{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->package->name ?? '—' }}</td>
                        <td>₦{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->pv }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No products found.</td></tr>
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