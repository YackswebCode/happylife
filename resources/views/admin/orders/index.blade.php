@extends('layouts.admin')

@section('title', 'Manage Orders - Admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Manage Orders</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Order #, customer name or email...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Order Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="">All</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-red w-100">
                            <i class="bi bi-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>Order Status</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><span class="badge bg-light text-dark">{{ $order->order_number }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 32px; height: 32px; background: var(--color-teal-blue);">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div>{{ $order->user->name ?? 'Deleted User' }}</div>
                                        <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold">{{ number_format($order->total, 2) }}</td>
                            <td>
                                @php
                                    $items = json_decode($order->items, true);
                                    $itemCount = is_array($items) ? count($items) : 0;
                                @endphp
                                {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                    ][$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>
                                @php
                                    $paymentClass = [
                                        'unpaid' => 'danger',
                                        'paid' => 'success',
                                        'refunded' => 'secondary',
                                    ][$order->payment_status] ?? 'warning';
                                @endphp
                                <span class="badge bg-{{ $paymentClass }}">{{ ucfirst($order->payment_status) }}</span>
                            </td>
                            <td><small>{{ $order->created_at->format('d M Y, H:i') }}</small></td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- No edit/delete buttons – view only --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection