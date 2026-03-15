@extends('layouts.admin')

@section('title', 'Funding Requests')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Funding Requests</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.funding-requests.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by user or transaction ID..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="payment_method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="paystack" {{ request('payment_method') == 'paystack' ? 'selected' : '' }}>Paystack</option>
                            <option value="flutterwave" {{ request('payment_method') == 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                            <!-- Add more as needed -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Funding Requests Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fundings as $funding)
                    <tr>
                        <td>#{{ $funding->id }}</td>
                        <td>
                            <div>
                                <div>{{ $funding->user->name ?? 'Deleted User' }}</div>
                                <small class="text-muted">{{ $funding->user->email ?? '' }}</small>
                            </div>
                        </td>
                        <td>₦{{ number_format($funding->amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $funding->payment_method)) }}</td>
                        <td><code>{{ $funding->transaction_id ?? '—' }}</code></td>
                        <td>
                            @if($funding->proof)
                                <a href="{{ asset('storage/'.$funding->proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-image"></i> View
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                ][$funding->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($funding->status) }}</span>
                        </td>
                        <td>{{ $funding->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.funding-requests.show', $funding) }}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No funding requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($fundings->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $fundings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection