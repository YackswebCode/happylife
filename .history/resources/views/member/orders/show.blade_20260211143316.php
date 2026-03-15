@extends('layouts.member')

@section('title', 'Order #'.$order->order_number.' - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header with back button -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('member.orders.index') }}" class="btn btn-outline-happylife-teal me-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Order #{{ $order->order_number }}</h1>
        <span class="ms-3">
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
            <span class="badge {{ $color }} fs-6 p-2">{{ ucfirst($order->status) }}</span>
        </span>
    </div>

    <div class="row g-4">
        <!-- Order Summary Card -->
        <div class="col-md-6">
            <div class="card product-card h-100 p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3 mb-3">
                    <i class="bi bi-receipt me-2"></i> Order Summary
                </h5>
                <table class="table table-borderless">
                    <tr>
                        <td class="ps-0 text-secondary">Order Date:</td>
                        <td class="fw-bold">{{ $order->created_at->format('F d, Y - h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="ps-0 text-secondary">Payment Method:</td>
                        <td class="fw-bold">
                            @if($order->payment_method == 'shopping_wallet')
                                Shopping Wallet
                            @else
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-0 text-secondary">Payment Status:</td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning text-dark">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                    @if($order->pv_total > 0)
                    <tr>
                        <td class="ps-0 text-secondary">Total PV Earned:</td>
                        <td class="fw-bold text-happylife-cyan">{{ $order->pv_total }} PV</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Customer Info Card (Optional) -->
        <div class="col-md-6">
            <div class="card product-card h-100 p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3 mb-3">
                    <i class="bi bi-person-circle me-2"></i> Customer Information
                </h5>
                <table class="table table-borderless">
                    <tr>
                        <td class="ps-0 text-secondary">Name:</td>
                        <td class="fw-bold">{{ $order->user->name ?? Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <td class="ps-0 text-secondary">Email:</td>
                        <td>{{ $order->user->email ?? Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <td class="ps-0 text-secondary">Phone:</td>
                        <td>{{ $order->user->phone ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-12">
            <div class="card product-card p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3 mb-3">
                    <i class="bi bi-box-seam me-2"></i> Order Items
                </h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 80px;"></th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>PV</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $items = is_array($order->items) ? $order->items : []; @endphp
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        @if(!empty($item['image']))
                                            <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" width="50" height="50" style="object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div class="bg-happylife-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 8px;">
                                                <i class="bi bi-image text-secondary"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $item['name'] }}</span>
                                        @if(!empty($item['sku']))
                                            <br><small class="text-secondary">SKU: {{ $item['sku'] }}</small>
                                        @endif
                                    </td>
                                    <td>₦{{ number_format($item['price'], 2) }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>
                                        @if(!empty($item['pv']))
                                            <span class="badge bg-happylife-cyan">{{ $item['pv'] }} PV</span>
                                        @else
                                            <span class="text-secondary">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-happylife-red text-end">
                                        ₦{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-secondary">
                                        Order items not available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Subtotal:</td>
                                <td class="text-end fw-bold">₦{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Shipping:</td>
                                <td class="text-end text-success fw-bold">Free</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold h5">Total:</td>
                                <td class="text-end fw-bold h5 text-happylife-red">₦{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Repurchase Bonus Info (if applicable) -->
                @if($order->payment_method == 'shopping_wallet')
                    <div class="alert alert-info mt-3 d-flex align-items-center" style="background-color: rgba(31, 163, 196, 0.1); border: 1px solid var(--happylife-teal);">
                        <i class="bi bi-gift-fill text-happylife-teal fs-4 me-3"></i>
                        <div>
                            <strong>Repurchase Bonus Earned:</strong> 
                            You earned <span class="fw-bold text-happylife-red">₦{{ number_format($order->subtotal * 0.05, 2) }}</span> 
                            (estimated) from this order.
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('member.orders.index') }}" class="btn btn-outline-happylife-teal">
                        <i class="bi bi-arrow-left me-1"></i> Back to Orders
                    </a>
                    @if($order->status == 'pending' || $order->status == 'processing')
                        <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle me-1"></i> Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection