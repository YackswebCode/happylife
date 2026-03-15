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
        <!-- empty cart display (unchanged) -->
    @else
        <div class="row">
            <div class="col-lg-8">
                <!-- cart items list (unchanged) -->
            </div>

            <div class="col-lg-4">
                <div class="card product-card p-4">
                    <h5 class="fw-bold text-happylife-dark border-bottom pb-3">Order Summary</h5>
                    
                    <!-- State & Pickup Centre Selection -->
                    <div class="mb-3">
                        <label for="state" class="form-label">Select State <span class="text-danger">*</span></label>
                        <select name="state_id" id="state" class="form-select" required>
                            <option value="">Choose state...</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pickup_center" class="form-label">Select Pickup Centre <span class="text-danger">*</span></label>
                        <select name="pickup_center_id" id="pickup_center" class="form-select" required disabled>
                            <option value="">First select a state</option>
                        </select>
                        <div id="pickup-details" class="mt-2 small text-muted"></div>
                    </div>

                    <!-- existing summary rows (subtotal, shipping, bonus) -->
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

                    <!-- Wallet balance -->
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
                        <!-- hidden fields to carry the selected values -->
                        <input type="hidden" name="state_id" id="hidden_state_id" value="">
                        <input type="hidden" name="pickup_center_id" id="hidden_pickup_center_id" value="">

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
        // Existing increment/decrement, update, remove code (unchanged) ...

        // ---------- State & Pickup Centre Handling ----------
        const stateSelect = document.getElementById('state');
        const pickupSelect = document.getElementById('pickup_center');
        const pickupDetails = document.getElementById('pickup-details');
        const hiddenState = document.getElementById('hidden_state_id');
        const hiddenPickup = document.getElementById('hidden_pickup_center_id');

        // Load pickup centres when state changes
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            hiddenState.value = stateId;  // update hidden field

            if (!stateId) {
                pickupSelect.disabled = true;
                pickupSelect.innerHTML = '<option value="">First select a state</option>';
                pickupDetails.innerHTML = '';
                hiddenPickup.value = '';
                return;
            }

            fetch(`/member/shopping/pickup-centers/${stateId}`)
                .then(response => response.json())
                .then(centers => {
                    pickupSelect.disabled = false;
                    pickupSelect.innerHTML = '<option value="">Select pickup centre</option>';
                    centers.forEach(center => {
                        pickupSelect.innerHTML += `<option value="${center.id}" data-address="${center.address}">${center.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading pickup centres:', error);
                    pickupSelect.disabled = true;
                    pickupSelect.innerHTML = '<option value="">Error loading centres</option>';
                });
        });

        // When pickup centre selected, show address and update hidden input
        pickupSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const address = selected.getAttribute('data-address');
            pickupDetails.innerHTML = address ? `<strong>Address:</strong> ${address}` : '';
            hiddenPickup.value = this.value;
        });

        // Before form submission, ensure both hidden fields are set
        document.querySelector('form[action$="checkout"]').addEventListener('submit', function(e) {
            if (!hiddenState.value || !hiddenPickup.value) {
                e.preventDefault();
                alert('Please select both state and pickup centre.');
            }
        });
    });
</script>
@endpush
@endsection