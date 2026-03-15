@extends('layouts.admin')

@section('title', 'Edit Repurchase Product')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Edit: {{ $repurchaseProduct->name }}</h2>
        <a href="{{ route('admin.repurchase-products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.repurchase-products.update', $repurchaseProduct) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $repurchaseProduct->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $repurchaseProduct->sku) }}" required>
                                <small class="text-muted">Unique product code</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $repurchaseProduct->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (₦) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $repurchaseProduct->price) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pv_value" class="form-label">PV Value <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('pv_value') is-invalid @enderror" id="pv_value" name="pv_value" value="{{ old('pv_value', $repurchaseProduct->pv_value) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $repurchaseProduct->stock) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $repurchaseProduct->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $repurchaseProduct->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (visible in store)
                                </label>
                            </div>
                        </div>

                        @if($repurchaseProduct->image)
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img src="{{ asset('storage/'.$repurchaseProduct->image) }}" alt="{{ $repurchaseProduct->name }}" style="max-width: 150px; max-height: 150px; border-radius: 5px;">
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">New Image (leave blank to keep current)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-red mt-3 px-4 py-2">Update Product</button>
    </form>
</div>
@endsection