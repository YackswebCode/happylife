@extends('layouts.admin')

@section('title', 'Edit Pickup Center')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Edit Pickup Center</h2>
        <a href="{{ route('admin.pickup-centers.index') }}" class="btn btn-outline-secondary">
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

    <form action="{{ route('admin.pickup-centers.update', $pickupCenter) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
                            <select class="form-select @error('state_id') is-invalid @enderror" id="state_id" name="state_id" required>
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id', $pickupCenter->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Center Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $pickupCenter->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $pickupCenter->address) }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">Contact Person <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $pickupCenter->contact_person) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $pickupCenter->contact_phone) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="operating_hours" class="form-label">Operating Hours <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('operating_hours') is-invalid @enderror" id="operating_hours" name="operating_hours" value="{{ old('operating_hours', $pickupCenter->operating_hours) }}" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $pickupCenter->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-red mt-3 px-4 py-2">Update Pickup Center</button>
    </form>
</div>
@endsection