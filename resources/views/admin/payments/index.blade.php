@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Payments</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="User, reference..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="payment_method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="paystack" {{ request('payment_method') == 'paystack' ? 'selected' : '' }}>Paystack</option>
                            <option value="flutterwave" {{ request('payment_method') == 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from_date" class="form-control" placeholder="From" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to_date" class="form-control" placeholder="To" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>#{{ $payment->id }}</td>
                            <td>{{ $payment->user->name ?? 'Deleted' }}</td>
                            <td>{{ $payment->package->name ?? '—' }}</td>
                            <td>₦{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td><code>{{ $payment->reference ?? '—' }}</code></td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary',
                                        'refunded' => 'info',
                                    ][$payment->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td>{{ $payment->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-4 text-muted">No payments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection