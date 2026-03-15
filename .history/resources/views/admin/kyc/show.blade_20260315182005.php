@extends('layouts.admin')

@section('title', 'KYC Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">KYC Verification #{{ $kyc->id }}</h2>
        <a href="{{ route('admin.kyc.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- User Info -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px;">Name:</th>
                            <td>{{ $kyc->user->name ?? 'Deleted User' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $kyc->user->email ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td>{{ $kyc->user->username ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $kyc->user->phone ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- KYC Status & Action -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Verification Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Status:</strong><br>
                        @php
                            $statusClass = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            ][$kyc->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $statusClass }} fs-6 p-2">{{ ucfirst($kyc->status) }}</span>
                    </div>

                    @if($kyc->verified_at)
                        <div class="mb-3">
                            <strong>Verified At:</strong> {{ $kyc->verified_at->format('d M Y, H:i') }}
                        </div>
                    @endif

                    @if($kyc->admin_comment)
                        <div class="mb-3">
                            <strong>Admin Comment:</strong>
                            <p class="text-muted">{{ $kyc->admin_comment }}</p>
                        </div>
                    @endif

                    <hr>

                    <form action="{{ route('admin.kyc.update', $kyc) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select class="form-select" id="status" name="status" {{ $kyc->status == 'approved' ? 'disabled' : '' }}>
                                <option value="pending" {{ $kyc->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $kyc->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                <option value="rejected" {{ $kyc->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="admin_comment" class="form-label">Admin Comment (optional)</label>
                            <textarea class="form-control" id="admin_comment" name="admin_comment" rows="3">{{ old('admin_comment', $kyc->admin_comment) }}</textarea>
                        </div>
                        @if($kyc->status != 'approved')
                            <button type="submit" class="btn btn-red">Update Status</button>
                        @else
                            <p class="text-muted">This KYC is already approved and cannot be changed.</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Document Details -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Document Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Document Type:</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $kyc->document_type)) }}</td>
                        </tr>
                        <tr>
                            <th>ID Number:</th>
                            <td>{{ $kyc->id_number ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Issue Date:</th>
                            <td>{{ $kyc->issue_date ? $kyc->issue_date->format('d M Y') : '—' }}</td>
                        </tr>
                        <tr>
                            <th>Expiry Date:</th>
                            <td>{{ $kyc->expiry_date ? $kyc->expiry_date->format('d M Y') : '—' }}</td>
                        </tr>
                        <tr>
                            <th>Place of Issue:</th>
                            <td>{{ $kyc->place_of_issue ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Submitted At:</th>
                            <td>{{ $kyc->submitted_at ? $kyc->submitted_at->format('d M Y, H:i') : $kyc->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Document Images -->
        <div class="col-12 mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Uploaded Images</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($kyc->front_image)
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Front Image</label>
                            <div>
                                <a href="{{ asset('storage/'.$kyc->front_image) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$kyc->front_image) }}" alt="Front" class="img-fluid rounded border" style="max-height: 200px;">
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($kyc->back_image)
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Back Image</label>
                            <div>
                                <a href="{{ asset('storage/'.$kyc->back_image) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$kyc->back_image) }}" alt="Back" class="img-fluid rounded border" style="max-height: 200px;">
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($kyc->selfie_image)
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Selfie Image</label>
                            <div>
                                <a href="{{ asset('storage/'.$kyc->selfie_image) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$kyc->selfie_image) }}" alt="Selfie" class="img-fluid rounded border" style="max-height: 200px;">
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(!$kyc->front_image && !$kyc->back_image && !$kyc->selfie_image)
                            <p class="text-muted">No images uploaded.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection