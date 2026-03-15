@extends('layouts.admin')

@section('title', 'Wallet Transactions')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Wallet Transactions</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.wallet-transactions.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search user, reference, description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="bonus" {{ request('type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                            <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>Commission</option>
                            <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Wallet</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                        <tr>
                            <td>#{{ $tx->id }}</td>
                            <td>{{ $tx->user->name ?? 'Deleted' }}</td>
                            <td>{{ $tx->wallet ? ucfirst($tx->wallet->type) : '—' }}</td>
                            <td><span class="badge bg-{{ $tx->type == 'credit' ? 'success' : ($tx->type == 'debit' ? 'danger' : 'info') }}">{{ ucfirst($tx->type) }}</span></td>
                            <td>₦{{ number_format($tx->amount, 2) }}</td>
                            <td>{{ Str::limit($tx->description, 30) }}</td>
                            <td><code>{{ $tx->reference }}</code></td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                    ][$tx->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($tx->status) }}</span>
                            </td>
                            <td>{{ $tx->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.wallet-transactions.show', $tx) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center py-4 text-muted">No transactions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection