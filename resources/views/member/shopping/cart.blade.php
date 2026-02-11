@extends('layouts.member')

@section('title', 'Shopping Cart - Happylife')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('member.shopping.index') }}" class="btn btn-outline-happylife-teal me-3">
            ← Continue Shopping
        </a>
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Your Cart</h1>
    </div>

    @if(empty($cartItems) || count($cartItems) == 0)
        <div class="text-center py-5 text-secondary">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <h4 class="mt-3">Your cart is empty</h4>
            <p>Looks like you haven't added anything yet.</p>
            <a href="{{ route('member.shopping.index') }}" class="btn btn-happylife-red mt-3 px-5 py-3">
                Start Shopping
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card product-card p-4 mb-4">
                    <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                        <span class="fw-bold">Product</span>
                        <span class="fw-bold">Price</span>
                        <span class="fw-bold">Quantity</span>
                        <span class="fw-bold">Subtotal</span>
                        <span class="fw-bold">Action</span>
                    </div>

                    @foreach($cartItems as $id => $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                            <div class="d-flex align-items-center" style="flex: 2;">
                                <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" width="60" height="60" style="object-fit: cover; border-radius: 8px;" class="me-3">
                                <div>
                                    <h6 class="fw-bold text-happylife-dark mb-0">{{ $item['name'] }}</h6>
                                    @if($item['pv'] > 0)
                                        <small class="text-happylife-cyan">PV: {{ $item['pv'] }}</small>
                                    @endif
                                </div>
                            </div>
                            <div style="flex: 1;" class="text-center">
                                ₦{{ number_format($item['price'], 2) }}
                            </div>
                            <div style="flex: 1;" class="text-center">
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('member.shopping.cart.update') }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="decrementCart(this)">−</button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" class="form-control form-control-sm mx-2 text-center" style="width: 60px;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="incrementCart(this)">+</button>
                                        <button type="submit" class="btn btn-sm btn-happylife-teal ms-2">Update</button>
                                    </form>
                                </div>
                            </div>
                            <div style="flex: 1;" class="text-center fw-bold text-happylife-red">
                                ₦{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </div>
                            <div style="flex: 0.5;" class="text-center">
                                <form action="{{ route('member.shopping.cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <button type="submit" class="btn btn-sm btn-link text-danger" onclick="return confirm('Remove this item?')">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0h10"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card product-card p-4">
                    <h5 class="fw-bold text-happylife-dark border-bottom pb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Subtotal</span>
                        <span class="fw-bold">₦{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Repurchase Bonus</span>
                        <span class="text-happylife-teal fw-bold">+ ₦{{ number_format($bonus_earned, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 fw-bold">Total</span>
                        <span class="h5 fw-bold text-happylife-red">₦{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <!-- Wallet balance -->
                    <div class="alert bg-happylife-light d-flex align-items-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-happylife-teal me-2">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                            <circle cx="16" cy="15" r="2"></circle>
                        </svg>
                        <div>
                            <small>Your Shopping Wallet</small>
                            <div class="fw-bold">₦{{ number_format(auth()->user()->shopping_wallet_balance ?? 0, 2) }}</div>
                        </div>
                    </div>

                    <form action="{{ route('member.shopping.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-happylife-red btn-lg w-100 py-3" 
                                {{ (auth()->user()->shopping_wallet_balance ?? 0) < $subtotal ? 'disabled' : '' }}>
                            Proceed to Checkout
                        </button>
                        @if((auth()->user()->shopping_wallet_balance ?? 0) < $subtotal)
                            <small class="text-danger d-block mt-2 text-center">
                                Insufficient shopping wallet balance. 
                                <a href="{{ route('member.wallet.funding') }}" class="text-happylife-red">Fund wallet</a>
                            </small>
                        @endif
                    </form>
                    <p class="text-center text-secondary small mt-3">
                        You will earn <span class="fw-bold text-happylife-red">₦{{ number_format($bonus_earned, 2) }}</span> repurchase bonus after payment.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function incrementCart(btn) {
        const input = btn.parentNode.querySelector('input[name="quantity"]');
        input.value = parseInt(input.value) + 1;
    }
    function decrementCart(btn) {
        const input = btn.parentNode.querySelector('input[name="quantity"]');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
@endsection