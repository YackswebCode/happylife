@extends('layouts.member')

@section('title', 'Order Receipt - Happylife')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card product-card p-4 text-center">
                <div class="mb-4">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="1">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                        <path d="M8 12l3 3 6-6" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <h2 class="fw-bold text-happylife-dark mb-3">Thank You for Your Purchase!</h2>
                <p class="text-secondary mb-4">Your order has been placed successfully.</p>

                <div class="alert bg-happylife-light text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Order Number:</span>
                        <span class="fw-bold">{{ $order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Order Date:</span>
                        <span class="fw-bold">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Payment Method:</span>
                        <span class="fw-bold">Shopping Wallet</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Total Paid:</span>
                        <span class="fw-bold text-happylife-red">₦{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Repurchase Bonus Earned:</span>
                        <span class="fw-bold text-happylife-teal">+ ₦{{ number_format($bonus_earned, 2) }}</span>
                    </div>
                </div>

                <h5 class="fw-bold text-happylife-dark mt-4 mb-3">Order Summary</h5>
                <div class="table-responsive">
                    <table class="table table-borderless text-start">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(json_decode($order->items, true) as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td class="text-end">₦{{ number_format($item['price'], 2) }}</td>
                                <td class="text-end">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ route('member.shopping.index') }}" class="btn btn-danger px-5 py-3">
                        Continue Shopping
                    </a>
                    <a href="{{ route('member.orders.index') }}" class="btn btn-danger px-5 py-3 ms-3">
                        View My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection