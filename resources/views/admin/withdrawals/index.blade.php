@extends('layouts.admin')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Withdrawal Requests</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.withdrawals.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by user or reference..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="wallet_type" class="form-select">
                            <option value="">All Wallets</option>
                            <option value="commission" {{ request('wallet_type') == 'commission' ? 'selected' : '' }}>Commission</option>
                            <option value="registration" {{ request('wallet_type') == 'registration' ? 'selected' : '' }}>Registration</option>
                            <option value="rank" {{ request('wallet_type') == 'rank' ? 'selected' : '' }}>Rank</option>
                            <option value="shopping" {{ request('wallet_type') == 'shopping' ? 'selected' : '' }}>Shopping</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Wallet</th>
                        <th>Amount</th>
                        <th>Net Amount</th>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                    <tr>
                        <td>#{{ $withdrawal->id }}</td>
                        <td>
                            <div>
                                <div>{{ $withdrawal->user->name ?? 'Deleted User' }}</div>
                                <small class="text-muted">{{ $withdrawal->user->email ?? '' }}</small>
                            </div>
                        </td>
                        <td>{{ ucfirst($withdrawal->wallet_type) }}</td>
                        <td>₦{{ number_format($withdrawal->amount, 2) }}</td>
                        <td>₦{{ number_format($withdrawal->net_amount, 2) }}</td>
                        <td><code>{{ $withdrawal->reference ?? '—' }}</code></td>
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
                        <td>{{ $withdrawal->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No withdrawal requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($withdrawals->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection