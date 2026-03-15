@extends('layouts.admin')

@section('title', 'Add Package')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Add New Package</h2>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
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

    <form action="{{ route('admin.packages.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (₦) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pv" class="form-label">PV <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('pv') is-invalid @enderror" id="pv" name="pv" value="{{ old('pv') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pairing_cap" class="form-label">Pairing Cap (₦) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('pairing_cap') is-invalid @enderror" id="pairing_cap" name="pairing_cap" value="{{ old('pairing_cap') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="direct_bonus_amount" class="form-label">Direct Bonus (₦) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('direct_bonus_amount') is-invalid @enderror" id="direct_bonus_amount" name="direct_bonus_amount" value="{{ old('direct_bonus_amount') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="indirect_bonus_amount" class="form-label">Indirect Bonus (₦)</label>
                                    <input type="number" step="0.01" class="form-control" id="indirect_bonus_amount" name="indirect_bonus_amount" value="{{ old('indirect_bonus_amount', 0) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="upgrade_bonus_amount" class="form-label">Upgrade Bonus (₦)</label>
                                    <input type="number" step="0.01" class="form-control" id="upgrade_bonus_amount" name="upgrade_bonus_amount" value="{{ old('upgrade_bonus_amount', 0) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="product_entitlement" class="form-label">Product Entitlement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('product_entitlement') is-invalid @enderror" id="product_entitlement" name="product_entitlement" value="{{ old('product_entitlement') }}" required>
                            <small class="text-muted">E.g., "1 Product", or list specific products</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (available for registration)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-red mt-3 px-4 py-2">Create Package</button>
    </form>
</div>
@endsection