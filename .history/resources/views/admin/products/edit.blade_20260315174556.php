@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Edit Product: {{ $product->name }}</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
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

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="package_id" class="form-label">Package (optional)</label>
                            <select class="form-select" id="package_id" name="package_id">
                                <option value="">None</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}" {{ old('package_id', $product->package_id) == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="pv" class="form-label">PV <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('pv') is-invalid @enderror" id="pv" name="pv" value="{{ old('pv', $product->pv) }}" required>
                        </div>

                        @if($product->image)
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="max-width: 150px; max-height: 150px; border-radius: 5px;">
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
        <button type="submit" class="btn btn-red mt-3">Update Product</button>
    </form>
</div>
@endsection