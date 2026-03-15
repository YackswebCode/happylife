@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Category: {{ $productCategory->name }}</h2>
        <div>
            <a href="{{ route('admin.product-categories.edit', $productCategory) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>Name:</th><td>{{ $productCategory->name }}</td></tr>
                        <tr><th>Slug:</th><td>{{ $productCategory->slug }}</td></tr>
                        <tr><th>Description:</th><td>{{ $productCategory->description ?? '—' }}</td></tr>
                        <tr><th>Sort Order:</th><td>{{ $productCategory->sort_order }}</td></tr>
                        <tr><th>Status:</th><td>@if($productCategory->is_active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</td></tr>
                        <tr><th>Created:</th><td>{{ $productCategory->created_at->format('d M Y, H:i') }}</td></tr>
                        <tr><th>Updated:</th><td>{{ $productCategory->updated_at->format('d M Y, H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($productCategory->image)
                        <img src="{{ asset('storage/'.$productCategory->image) }}" alt="{{ $productCategory->name }}" class="img-fluid rounded" style="max-height: 200px;">
                    @else
                        <p class="text-muted">No image</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection