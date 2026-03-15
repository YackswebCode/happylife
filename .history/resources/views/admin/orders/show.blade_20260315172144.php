@extends('layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Order Details: {{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    <!-- Order Info Cards -->
    <div class="row g-4">
        <!-- Basic Order Info -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-red"></i>Order Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Order Number:</th>
                            <td>{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <th>Order Status:</th>
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
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
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
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ $order->payment_method ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Subtotal:</th>
                            <td>{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td class="fw-bold">{{ number_format($order->total, 2) }}</td>
                        </tr>
                        <tr>
                            <th>PV Total:</th>
                            <td>{{ $order->pv_total }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $order->created_at->format('d M Y, H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer & Pickup Info -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-circle me-2 text-teal-blue"></i>Customer & Pickup</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Customer</h6>
                    <p class="mb-1">{{ $order->user->name ?? 'Deleted User' }}</p>
                    <p class="mb-1">{{ $order->user->email ?? '' }}</p>
                    <p class="mb-3">{{ $order->user->phone ?? '' }}</p>

                    <h6 class="fw-bold">Pickup Information</h6>
                    <p class="mb-1">State: {{ $order->state_name ?? '—' }}</p>
                    <p class="mb-1">Pickup Center: {{ $order->pickup_center_name ?? '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-soft-cyan"></i>Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr>
                                    <td>
                                        {{ $item['name'] ?? 'N/A' }}
                                        @if(!empty($item['options']))
                                            <small class="text-muted d-block">{{ json_encode($item['options']) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item['price'] ?? 0, 2) }}</td>
                                    <td>{{ $item['quantity'] ?? 1 }}</td>
                                    <td class="fw-bold">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">No items found (malformed JSON?)</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal:</th>
                                    <th>{{ number_format($order->subtotal, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="fw-bold text-red">{{ number_format($order->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection