@extends('layouts.admin')

@section('title', 'Repurchase Product Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Product: {{ $repurchaseProduct->name }}</h2>
        <div>
            <a href="{{ route('admin.repurchase-products.edit', $repurchaseProduct) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.repurchase-products.index') }}" class="btn btn-outline-secondary">
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
                            <td>{{ $repurchaseProduct->name }}</td>
                        </tr>
                        <tr>
                            <th>SKU:</th>
                            <td><code>{{ $repurchaseProduct->sku }}</code></td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td>{{ $repurchaseProduct->category->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>₦{{ number_format($repurchaseProduct->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>PV Value:</th>
                            <td>{{ $repurchaseProduct->pv_value }}</td>
                        </tr>
                        <tr>
                            <th>Stock:</th>
                            <td>
                                @if($repurchaseProduct->stock > 10)
                                    <span class="badge bg-success">{{ $repurchaseProduct->stock }}</span>
                                @elseif($repurchaseProduct->stock > 0)
                                    <span class="badge bg-warning">{{ $repurchaseProduct->stock }} (low stock)</span>
                                @else
                                    <span class="badge bg-danger">Out of stock</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $repurchaseProduct->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($repurchaseProduct->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $repurchaseProduct->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $repurchaseProduct->updated_at->format('d M Y, H:i') }}</td>
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
                    @if($repurchaseProduct->image)
                        <img src="{{ asset('storage/'.$repurchaseProduct->image) }}" alt="{{ $repurchaseProduct->name }}" class="img-fluid rounded" style="max-height: 300px;">
                    @else
                        <p class="text-muted">No image uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection