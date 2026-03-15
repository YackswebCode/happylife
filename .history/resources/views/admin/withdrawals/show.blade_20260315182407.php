@extends('layouts.admin')

@section('title', 'Withdrawal Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Withdrawal #{{ $withdrawal->id }}</h2>
        <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-outline-secondary">
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
                            <td>{{ $withdrawal->user->name ?? 'Deleted User' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $withdrawal->user->email ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td>{{ $withdrawal->user->username ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $withdrawal->user->phone ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Withdrawal Details -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Withdrawal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px;">Wallet:</th>
                            <td>{{ ucfirst($withdrawal->wallet_type) }}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>₦{{ number_format($withdrawal->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Fee:</th>
                            <td>₦{{ number_format($withdrawal->fee, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Net Amount:</th>
                            <td>₦{{ number_format($withdrawal->net_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Reference:</th>
                            <td><code>{{ $withdrawal->reference ?? '—' }}</code></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'info',
                                        'rejected' => 'danger',
                                        'processed' => 'success',
                                    ][$withdrawal->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($withdrawal->status) }}</span>
                            </td>
                        </tr>
                        @if($withdrawal->processed_at)
                        <tr>
                            <th>Processed At:</th>
                            <td>{{ $withdrawal->processed_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Requested At:</th>
                            <td>{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Bank Details</h5>
                </div>
                <div class="card-body">
                    @if($withdrawal->bank_details)
                        <table class="table table-borderless">
                            @foreach($withdrawal->bank_details as $key => $value)
                                <tr>
                                    <th style="width: 150px;">{{ ucfirst(str_replace('_', ' ', $key)) }}:</th>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p class="text-muted">No bank details provided.</p>
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
                    @if($withdrawal->admin_notes)
                        <div class="mb-3">
                            <strong>Current Admin Notes:</strong>
                            <p class="text-muted">{{ $withdrawal->admin_notes }}</p>
                        </div>
                    @endif

                    <form action="{{ route('admin.withdrawals.update', $withdrawal) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" {{ $withdrawal->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $withdrawal->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                    <option value="rejected" {{ $withdrawal->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                    <option value="processed" {{ $withdrawal->status == 'processed' ? 'selected' : '' }}>Mark as Processed</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="admin_notes" class="form-label">Admin Notes</label>
                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="2">{{ old('admin_notes', $withdrawal->admin_notes) }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-red">Update Withdrawal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection