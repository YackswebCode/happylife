@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Product: {{ $product->name }}</h2>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Name:</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Package:</th>
                            <td>{{ $product->package->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>₦{{ number_format($product->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>PV:</th>
                            <td>{{ $product->pv }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $product->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $product->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Product Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 300px;">
                    @else
                        <p class="text-muted">No image uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection