@extends('layouts.admin')

@section('title', 'VTU Transactions')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">VTU Transactions</h2>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vtu.transactions.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search user, reference, phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="service_type" class="form-select">
                            <option value="">All Services</option>
                            <option value="airtime" {{ request('service_type') == 'airtime' ? 'selected' : '' }}>Airtime</option>
                            <option value="data" {{ request('service_type') == 'data' ? 'selected' : '' }}>Data</option>
                            <option value="cable" {{ request('service_type') == 'cable' ? 'selected' : '' }}>Cable TV</option>
                            <option value="electricity" {{ request('service_type') == 'electricity' ? 'selected' : '' }}>Electricity</option>
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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Service</th>
                            <th>Provider</th>
                            <th>Phone/Meter</th>
                            <th>Amount</th>
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
                            <td>{{ ucfirst($tx->service_type) }}</td>
                            <td>{{ $tx->provider->name ?? '—' }}</td>
                            <td>{{ $tx->phone ?? $tx->smart_card ?? $tx->meter_number ?? '—' }}</td>
                            <td>₦{{ number_format($tx->amount, 2) }}</td>
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
                                <a href="{{ route('admin.vtu.transactions.show', $tx) }}" class="btn btn-sm btn-outline-info">
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