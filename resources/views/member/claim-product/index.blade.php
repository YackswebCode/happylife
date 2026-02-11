@extends('layouts.member')

@section('title', 'Claim Product - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-happylife-dark">Claim Your Product</h1>
            <p class="text-secondary">Collect the physical product you selected during registration</p>
        </div>
    </div>

    <div class="row g-4">
        @if($claim)
            <!-- Already claimed – show status and receipt -->
            <div class="col-lg-8">
                <div class="card product-card p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-happylife-teal bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-gift-fill fs-3 text-happylife-teal"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Claim Submitted</h5>
                            <p class="text-secondary mb-0">Reference: {{ $claim->claim_number }}</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <small class="text-secondary d-block">Product</small>
                                <span class="fw-bold">{{ $product->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <small class="text-secondary d-block">Status</small>
                                @if($claim->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                @elseif($claim->status == 'approved')
                                    <span class="badge bg-success">Approved – Ready for Pickup</span>
                                @elseif($claim->status == 'collected')
                                    <span class="badge bg-happylife-teal">Collected</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($claim->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light p-3 rounded">
                                <small class="text-secondary d-block">Pickup Center</small>
                                <span class="fw-bold">{{ $claim->pickupCenter->name }}</span><br>
                                <span>{{ $claim->pickupCenter->address }}</span><br>
                                <small>Contact: {{ $claim->pickupCenter->contact_person }} ({{ $claim->pickupCenter->contact_phone }})</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-3">
                        <a href="{{ route('member.claim-product.receipt', $claim->id) }}" class="btn btn-happylife-teal">
                            <i class="bi bi-printer me-2"></i> Print Receipt
                        </a>
                        @if($claim->status == 'pending')
                            <form action="{{ route('member.claim-product.cancel', $claim->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Cancel this claim?')">
                                    <i class="bi bi-x-circle me-2"></i> Cancel Claim
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card product-card p-4">
                    <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                        <i class="bi bi-info-circle me-2"></i> Important
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Present your receipt at the pickup center.</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Bring a valid ID for verification.</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Product must be collected within 30 days.</li>
                    </ul>
                </div>
            </div>
        @else
            <!-- No claim yet – show form -->
            <div class="col-lg-8">
                <div class="card product-card p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-happylife-red bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-box-seam fs-3 text-happylife-red"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Your Product: {{ $product->name }}</h5>
                            <p class="text-secondary mb-0">Package value: ₦{{ number_format($product->price, 2) }} · PV: {{ $product->pv }}</p>
                        </div>
                    </div>

                    <form action="{{ route('member.claim-product.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Select Pickup Center</label>
                                <select name="pickup_center_id" class="form-select" required>
                                    <option value="">-- Choose your preferred pickup location --</option>
                                    @foreach($pickupCenters as $center)
                                        <option value="{{ $center->id }}">
                                            {{ $center->name }} – {{ $center->state->name ?? '' }} ({{ $center->address }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-secondary">You will receive your product at this location.</small>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-happylife-red btn-lg w-100 py-3">
                                    <i class="bi bi-check2-circle me-2"></i> Submit Claim Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card product-card p-4">
                    <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                        <i class="bi bi-geo-alt-fill me-2"></i> Need Help?
                    </h5>
                    <p class="text-secondary">Can't find your state or center? Contact our support team.</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-happylife-teal w-100">
                        <i class="bi bi-headset me-2"></i> Contact Support
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection