@extends('layouts.admin')

@section('title', 'Funding Request Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Funding Request #{{ $fundingRequest->id }}</h2>
        <a href="{{ route('admin.funding-requests.index') }}" class="btn btn-outline-secondary">
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
                            <td>{{ $fundingRequest->user->name ?? 'Deleted User' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $fundingRequest->user->email ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td>{{ $fundingRequest->user->username ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $fundingRequest->user->phone ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Funding Details -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Request Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px;">Amount:</th>
                            <td>₦{{ number_format($fundingRequest->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $fundingRequest->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th>Transaction ID:</th>
                            <td><code>{{ $fundingRequest->transaction_id ?? '—' }}</code></td>
                        </tr>
                        <tr>
                            <th>User Notes:</th>
                            <td>{{ $fundingRequest->notes ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                    ][$fundingRequest->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($fundingRequest->status) }}</span>
                            </td>
                        </tr>
                        @if($fundingRequest->approved_at)
                        <tr>
                            <th>Approved At:</th>
                            <td>{{ $fundingRequest->approved_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Requested At:</th>
                            <td>{{ $fundingRequest->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Proof of Payment -->
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Proof of Payment</h5>
                </div>
                <div class="card-body">
                    @if($fundingRequest->proof)
                        <div class="text-center">
                            <a href="{{ asset('storage/'.$fundingRequest->proof) }}" target="_blank">
                                <img src="{{ asset('storage/'.$fundingRequest->proof) }}" alt="Proof" class="img-fluid rounded border" style="max-height: 400px;">
                            </a>
                            <p class="mt-2">
                                <a href="{{ asset('storage/'.$fundingRequest->proof) }}" download class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </p>
                        </div>
                    @else
                        <p class="text-muted">No proof uploaded.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Notes & Status Update -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    @if($fundingRequest->admin_notes)
                        <div class="mb-3">
                            <strong>Current Admin Notes:</strong>
                            <p class="text-muted">{{ $fundingRequest->admin_notes }}</p>
                        </div>
                    @endif

                    <form action="{{ route('admin.funding-requests.update', $fundingRequest) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" id="status" name="status" {{ $fundingRequest->status == 'approved' ? 'disabled' : '' }}>
                                    <option value="pending" {{ $fundingRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $fundingRequest->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                    <option value="rejected" {{ $fundingRequest->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="admin_notes" class="form-label">Admin Notes</label>
                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="2">{{ old('admin_notes', $fundingRequest->admin_notes) }}</textarea>
                            </div>
                        </div>
                        @if($fundingRequest->status != 'approved')
                            <button type="submit" class="btn btn-red">Update Request</button>
                        @else
                            <p class="text-muted">This request is already approved and cannot be changed.</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection