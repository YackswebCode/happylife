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
                        <div class="d-flex justify-content-between align-items-center border-bottom py-3 cart-item" data-id="{{ $id }}">
                            <div class="d-flex align-items-center" style="flex: 2;">
                                <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" width="60" height="60" style="object-fit: cover; border-radius: 8px;" class="me-3">
                                <div>
                                    <h6 class="fw-bold text-happylife-dark mb-0">{{ $item['name'] }}</h6>
                                    @if($item['pv'] > 0)
                                        <small class="text-happylife-cyan">PV: {{ $item['pv'] }}</small>
                                    @endif
                                </div>
                            </div>
                            <div style="flex: 1;" class="text-center item-price">
                                ₦{{ number_format($item['price'], 2) }}
                            </div>
                            <div style="flex: 1;" class="text-center">
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('member.shopping.cart.update') }}" method="POST" class="d-flex align-items-center cart-update-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button type="button" class="btn btn-sm btn-outline-secondary decrement-btn">−</button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" class="form-control form-control-sm mx-2 text-center quantity-input" style="width: 60px;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary increment-btn">+</button>
                                        <button type="submit" class="btn btn-sm btn-happylife-teal ms-2 update-btn">Update</button>
                                    </form>
                                </div>
                            </div>
                            <div style="flex: 1;" class="text-center fw-bold text-happylife-red item-subtotal">
                                ₦{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </div>
                            <div style="flex: 0.5;" class="text-center">
                                <form action="{{ route('member.shopping.cart.remove') }}" method="POST" class="cart-remove-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <button type="submit" class="btn btn-sm btn-link text-danger">
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
                        <span class="fw-bold subtotal-amount">₦{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Repurchase Bonus</span>
                        <span class="text-happylife-teal fw-bold bonus-amount">+ ₦{{ number_format($bonus_earned, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 fw-bold">Total</span>
                        <span class="h5 fw-bold text-happylife-red total-amount">₦{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <!-- Wallet balance (fixed) -->
                    <div class="alert bg-happylife-light d-flex align-items-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-happylife-teal me-2">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                            <circle cx="16" cy="15" r="2"></circle>
                        </svg>
                        <div>
                            <small>Your Shopping Wallet</small>
                            <div class="fw-bold wallet-balance">₦{{ number_format(auth()->user()->shopping_wallet_balance, 2) }}</div>
                        </div>
                    </div>

                    <form action="{{ route('member.shopping.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-happylife-red btn-danger btn-lg w-100 py-3 checkout-btn"
                                {{ auth()->user()->shopping_wallet_balance < $subtotal ? 'disabled' : '' }}>
                            Proceed to Checkout
                        </button>
                        @if(auth()->user()->shopping_wallet_balance < $subtotal)
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
    document.addEventListener('DOMContentLoaded', function() {
        // ---------- Increment / Decrement buttons ----------
        document.querySelectorAll('.increment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.closest('.cart-update-form').querySelector('.quantity-input');
                input.value = parseInt(input.value) + 1;
            });
        });

        document.querySelectorAll('.decrement-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.closest('.cart-update-form').querySelector('.quantity-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            });
        });

        // ---------- AJAX Update Quantity ----------
        document.querySelectorAll('.cart-update-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const submitBtn = form.querySelector('.update-btn');
                const originalText = submitBtn.innerText;

                // Loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';

                try {
                    const response = await fetch('{{ route("member.shopping.cart.update") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'   // ✅ CRITICAL
                        }
                    });

                    if (!response.ok) {
                        let errorMsg = `HTTP error ${response.status}`;
                        try {
                            const errorData = await response.json();
                            errorMsg = errorData.message || errorMsg;
                        } catch (e) {
                            // Not JSON
                        }
                        throw new Error(errorMsg);
                    }

                    const data = await response.json();
                    if (data.success) {
                        updateCartTotals(data);
                        showToast('Cart updated', 'success');
                    } else {
                        showToast(data.message || 'Update failed', 'danger');
                    }
                } catch (error) {
                    console.error('Update error:', error);
                    showToast(error.message || 'An error occurred', 'danger');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerText = originalText;
                }
            });
        });

        // ---------- AJAX Remove Item ----------
        document.querySelectorAll('.cart-remove-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!confirm('Remove this item?')) return;

                const formData = new FormData(form);
                const row = form.closest('.cart-item');

                try {
                    const response = await fetch('{{ route("member.shopping.cart.remove") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'   // ✅ CRITICAL
                        }
                    });

                    if (!response.ok) {
                        let errorMsg = `HTTP error ${response.status}`;
                        try {
                            const errorData = await response.json();
                            errorMsg = errorData.message || errorMsg;
                        } catch (e) {}
                        throw new Error(errorMsg);
                    }

                    const data = await response.json();
                    if (data.success) {
                        row.remove();
                        updateCartTotals(data);
                        updateCartBadge(data.cart_count);
                        showToast('Item removed', 'success');

                        if (data.cart_count === 0) {
                            location.reload(); // reload to show empty state
                        }
                    } else {
                        showToast(data.message || 'Remove failed', 'danger');
                    }
                } catch (error) {
                    console.error('Remove error:', error);
                    showToast(error.message || 'An error occurred', 'danger');
                }
            });
        });

        // ---------- Helper Functions ----------
        function updateCartTotals(data) {
            if (data.subtotal !== undefined) {
                document.querySelector('.subtotal-amount').innerText = '₦' + formatNumber(data.subtotal);
                document.querySelector('.total-amount').innerText = '₦' + formatNumber(data.subtotal);
            }
            if (data.bonus_earned !== undefined) {
                document.querySelector('.bonus-amount').innerHTML = '+ ₦' + formatNumber(data.bonus_earned);
            }
            if (data.items) {
                data.items.forEach(item => {
                    const row = document.querySelector(`.cart-item[data-id="${item.id}"]`);
                    if (row) {
                        row.querySelector('.item-subtotal').innerText = '₦' + formatNumber(item.subtotal);
                    }
                });
            }
            updateCheckoutButton(data.subtotal);
        }

        function updateCheckoutButton(subtotal) {
            const walletBalanceEl = document.querySelector('.wallet-balance');
            if (!walletBalanceEl) return;
            const walletBalance = parseFloat(walletBalanceEl.innerText.replace(/[₦,]/g, '')) || 0;
            const checkoutBtn = document.querySelector('.checkout-btn');
            if (checkoutBtn) {
                if (walletBalance < subtotal) {
                    checkoutBtn.disabled = true;
                    let msg = document.querySelector('.insufficient-warning');
                    if (!msg) {
                        msg = document.createElement('small');
                        msg.className = 'text-danger d-block mt-2 text-center insufficient-warning';
                        msg.innerHTML = 'Insufficient shopping wallet balance. <a href="{{ route("member.wallet.funding") }}" class="text-happylife-red">Fund wallet</a>';
                        checkoutBtn.parentNode.appendChild(msg);
                    }
                } else {
                    checkoutBtn.disabled = false;
                    const msg = document.querySelector('.insufficient-warning');
                    if (msg) msg.remove();
                }
            }
        }

        function updateCartBadge(count) {
            const cartBtn = document.querySelector('.btn-happylife-red');
            let badge = cartBtn.querySelector('.cart-count, .badge');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark cart-count';
                    cartBtn.appendChild(badge);
                }
                badge.textContent = count;
            } else {
                if (badge) badge.remove();
            }
        }

        function formatNumber(num) {
            return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function showToast(message, type = 'success') {
            const toastContainer = document.createElement('div');
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            toastContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.body.appendChild(toastContainer);
            setTimeout(() => toastContainer.remove(), 3000);
        }
    });
</script>
@endpush
@endsection