@extends('layouts.admin')

@section('title', 'Create Rank')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Create New Rank</h2>
        <a href="{{ route('admin.ranks.index') }}" class="btn btn-outline-secondary">
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

    <form action="{{ route('admin.ranks.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Rank Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="level" class="form-label">Level <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('level') is-invalid @enderror" id="level" name="level" value="{{ old('level') }}" required min="1">
                                    <small class="text-muted">Must be unique</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="required_pv" class="form-label">Required PV <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('required_pv') is-invalid @enderror" id="required_pv" name="required_pv" value="{{ old('required_pv') }}" required min="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cash_reward" class="form-label">Cash Reward (₦) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('cash_reward') is-invalid @enderror" id="cash_reward" name="cash_reward" value="{{ old('cash_reward') }}" required min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="other_reward" class="form-label">Other Reward (optional)</label>
                                    <input type="text" class="form-control" id="other_reward" name="other_reward" value="{{ old('other_reward') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-red mt-3 px-4 py-2">Create Rank</button>
    </form>
</div>
@endsection