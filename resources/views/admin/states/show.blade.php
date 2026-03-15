@extends('layouts.admin')

@section('title', 'State Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">State: {{ $state->name }}</h2>
        <div>
            <a href="{{ route('admin.states.edit', $state) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.states.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">State Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px;">Name:</th>
                            <td>{{ $state->name }}</td>
                        </tr>
                        <tr>
                            <th>Code:</th>
                            <td><code>{{ $state->code }}</code></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($state->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Pickup Centers:</th>
                            <td>{{ $state->pickupCenters()->count() }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $state->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $state->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection