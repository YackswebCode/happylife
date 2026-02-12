@extends('layouts.member')

@section('title', $product->name . ' - Happylife')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('member.shopping.index') }}" class="btn btn-outline-happylife-teal">
            ← Back to Mall
        </a>
    </div>

    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card product-card p-3">
                <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid rounded-4" alt="{{ $product->name }}">
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <div class="card product-card p-4 h-100">
                <h1 class="h2 fw-bold text-happylife-dark">{{ $product->name }}</h1>
                
                @if($product->category)
                    <div class="mb-2">
                        <span class="badge bg-happylife-light text-happylife-dark px-3 py-2">
                            {{ $product->category->name }}
                        </span>
                    </div>
                @endif

                <div class="d-flex align-items-center mt-3">
                    <span class="display-6 fw-bold text-happylife-red">₦{{ number_format($product->price, 2) }}</span>
                    @if($product->old_price > $product->price)
                        <span class="ms-3 text-decoration-line-through text-secondary fs-5">₦{{ number_format($product->old_price, 2) }}</span>
                    @endif
                </div>

                @if($product->pv_value > 0)
                    <div class="mt-2">
                        <span class="badge bg-happylife-cyan text-white px-3 py-2 fs-6">
                            PV: {{ $product->pv_value }}
                        </span>
                    </div>
                @endif

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="fw-bold text-happylife-dark">Description</h5>
                    <p class="text-secondary">{{ $product->description }}</p>
                </div>

                <!-- Shopping Bonus Info -->
                <div class="alert alert-info d-flex align-items-center" style="background-color: rgba(31, 163, 196, 0.1); border: 1px solid var(--happylife-teal);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-happylife-teal me-3">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4M12 8h.01"></path>
                    </svg>
                    <span>
                        <strong>Repurchase Bonus:</strong> You earn <span class="fw-bold text-happylife-red">₦250</span> for every product purchased!
                    </span>
                </div>

                <!-- Add to Cart (AJAX) -->
                <div class="mt-3">
                    <form action="{{ route('member.shopping.cart.add') }}" method="POST" id="addToCartForm" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="d-flex align-items-center mb-4">
                            <label for="quantity" class="fw-bold me-3">Quantity</label>
                            <div class="input-group" style="width: 140px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="decrementQuantity()">−</button>
                                <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="99">
                                <button class="btn btn-outline-secondary" type="button" onclick="incrementQuantity()">+</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-happylife-red btn-danger btn-lg w-100 py-3" id="addToCartBtn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-5">
            <h3 class="fw-bold text-happylife-dark mb-4">You may also like</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card product-card h-100">
                            <img src="{{ asset('storage/'.$related->image) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 160px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="fw-bold text-happylife-dark">{{ $related->name }}</h6>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-happylife-red">₦{{ number_format($related->price, 2) }}</span>
                                    <a href="{{ route('member.shopping.product', $related->id) }}" class="btn btn-danger btn-outline-happylife-teal btn-sm">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // ---------- Quantity controls ----------
    window.incrementQuantity = function() {
        const input = document.getElementById('quantity');
        input.value = parseInt(input.value) + 1;
    };
    window.decrementQuantity = function() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    };

    // ---------- AJAX Add to Cart ----------
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addToCartForm');
        if (!form) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Adding...';

            try {
                const response = await fetch('{{ route("member.shopping.cart.add") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
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
                    updateCartBadge(data.cart_count);
                    showToast('Product added to cart!', 'success');
                } else {
                    showToast(data.message || 'Failed to add product', 'danger');
                }
            } catch (error) {
                console.error('Add to cart error:', error);
                showToast(error.message || 'An error occurred', 'danger');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // ---------- Update cart badge (same as in index) ----------
        function updateCartBadge(count) {
            const cartBtn = document.querySelector('.btn-happylife-red');
            if (!cartBtn) return; // not on this page? (but safe)

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

        // ---------- Toast notification ----------
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