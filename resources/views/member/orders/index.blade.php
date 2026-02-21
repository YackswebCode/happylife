@extends('layouts.member')

@section('title', 'My Orders - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-happylife-dark">My Orders</h1>
            <p class="text-secondary">View your order history and status</p>
        </div>
        <a href="{{ route('member.shopping.index') }}" class="btn btn-danger btn-happylife-red">
            <i class="bi bi-cart-plus me-2"></i> Continue Shopping
        </a>
    </div>

    <!-- Orders List -->
    <div class="card product-card p-4">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="ps-3 fw-bold text-happylife-dark">
                                    {{ $order->order_number }}
                                </td>
                                <td>
                                    {{ $order->created_at->format('M d, Y') }}
                                    <small class="d-block text-secondary">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @php
                                        $itemCount = is_array($order->items) ? count($order->items) : 0;
                                    @endphp
                                    <span class="badge bg-happylife-teal">{{ $itemCount }} item(s)</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-happylife-red">₦{{ number_format($order->total, 2) }}</span>
                                </td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($order->payment_status == 'unpaid')
                                        <span class="badge bg-warning text-dark">Unpaid</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-white',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            'shipped' => 'bg-primary',
                                            'delivered' => 'bg-happylife-teal',
                                        ];
                                        $color = $statusColors[$order->status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $color }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('member.orders.show', $order) }}" class="btn btn-danger btn-sm btn-outline-happylife-teal">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-5 text-secondary">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="3" width="20" height="19" rx="2" ry="2"></rect>
                    <line x1="8" y1="9" x2="16" y2="9"></line>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="12" y2="17"></line>
                </svg>
                <h4 class="mt-3">No orders yet</h4>
                <p>You haven't placed any orders. Start shopping now!</p>
                <a href="{{ route('member.shopping.index') }}" class="btn btn-danger btn-happylife-red mt-3 px-5 py-3">
                    <i class="bi bi-cart me-2"></i> Shop Now
                </a>
            </div>
        @endif
    </div>
</div>
@endsection