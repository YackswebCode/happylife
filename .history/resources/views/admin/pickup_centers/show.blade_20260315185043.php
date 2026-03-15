@extends('layouts.admin')

@section('title', 'Pickup Center Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">{{ $pickupCenter->name }}</h2>
        <div>
            <a href="{{ route('admin.pickup-centers.edit', $pickupCenter) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.pickup-centers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Center Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Name:</th>
                            <td>{{ $pickupCenter->name }}</td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td>{{ $pickupCenter->state->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $pickupCenter->address }}</td>
                        </tr>
                        <tr>
                            <th>Contact Person:</th>
                            <td>{{ $pickupCenter->contact_person }}</td>
                        </tr>
                        <tr>
                            <th>Contact Phone:</th>
                            <td>{{ $pickupCenter->contact_phone }}</td>
                        </tr>
                        <tr>
                            <th>Operating Hours:</th>
                            <td>{{ $pickupCenter->operating_hours }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($pickupCenter->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $pickupCenter->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $pickupCenter->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection