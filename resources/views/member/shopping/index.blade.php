@extends('layouts.member')

@section('title', 'Repurchase Mall - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header with wallet balance -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-happylife-dark">Repurchase Mall</h1>
            <p class="text-secondary">Shop with your shopping wallet & earn ₦250 bonus per product</p>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('member.wallet.shopping') }}" class="btn btn-outline-happylife-teal d-flex align-items-center">
                <span class="me-2">🛒</span> Shopping Wallet: 
                <span class="fw-bold ms-1">₦{{ number_format(auth()->user()->shopping_wallet_balance ?? 0, 2) }}</span>
            </a>
            <a href="{{ route('member.shopping.cart') }}" class="btn btn-happylife-red d-flex align-items-center position-relative">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="ms-2">Cart</span>
                @if(session()->has('cart') && count(session('cart')) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <!-- Filters / Categories -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="btn-group flex-wrap" role="group">
                <a href="{{ route('member.shopping.index') }}" class="btn btn-outline-happylife-teal {{ !request('category') ? 'active' : '' }}">All</a>
                @foreach($categories ?? [] as $cat)
                    <a href="{{ route('member.shopping.index', ['category' => $cat->id]) }}" 
                       class="btn btn-outline-happylife-teal {{ request('category') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="col-md-4">
            <form action="{{ route('member.shopping.index') }}" method="GET" class="d-flex">
                <input type="search" name="search" class="form-control me-2" placeholder="Search products..." value="{{ request('search') }}">
                <button class="btn btn-happylife-teal" type="submit">Search</button>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products ?? [] as $product)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 220px; object-fit: cover; border-radius: 20px 20px 0 0;">
                        @if($product->pv_value > 0)
                            <span class="position-absolute top-0 end-0 bg-happylife-cyan text-white px-3 py-1 m-2 rounded-pill small">
                                PV: {{ $product->pv_value }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-happylife-dark">{{ $product->name }}</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h4 fw-bold text-happylife-red">₦{{ number_format($product->price, 2) }}</span>
                            @if($product->old_price > $product->price)
                                <small class="text-decoration-line-through text-secondary">₦{{ number_format($product->old_price, 2) }}</small>
                            @endif
                        </div>
                        <p class="card-text text-secondary small">{{ Str::limit($product->description, 60) }}</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('member.shopping.product', $product->id) }}" class="btn btn-outline-happylife-teal flex-grow-1">
                                View
                            </a>
                            <button class="btn btn-happylife-red flex-grow-1 add-to-cart" data-id="{{ $product->id }}">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 text-secondary">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <h4>No products found</h4>
                    <p>Check back later for new arrivals.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($products) && method_exists($products, 'links'))
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Simple add-to-cart with Alpine or vanilla JS
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            fetch('/member/shopping/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update cart count badge
                    const badge = document.querySelector('.btn-happylife-red .badge');
                    if (badge) badge.textContent = data.cart_count;
                    else {
                        const cartBtn = document.querySelector('.btn-happylife-red');
                        const newBadge = document.createElement('span');
                        newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark';
                        newBadge.textContent = data.cart_count;
                        cartBtn.appendChild(newBadge);
                    }
                    alert('Product added to cart!');
                }
            });
        });
    });
</script>
@endpush
@endsection