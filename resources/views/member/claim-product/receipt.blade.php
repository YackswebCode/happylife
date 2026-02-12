@extends('layouts.member')

@section('title', 'Claim Receipt - Happylife')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Print Button (visible only on screen) -->
            <div class="d-flex justify-content-end mb-3 no-print">
                <button onclick="window.print()" class="btn btn-happylife-teal">
                    <i class="bi bi-printer me-2"></i> Print Receipt
                </button>
                <a href="{{ route('member.claim-product.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <!-- Receipt Card -->
            <div class="card product-card p-4 shadow-sm" id="receipt">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-happylife-red">HAPPYLIFE MULTIPURPOSE INT'L</h2>
                    <p class="text-secondary">Product Claim Receipt</p>
                    <div class="border-top border-2 border-happylife-red mx-auto" style="width: 80px;"></div>
                </div>

                <!-- PRODUCT TO COLLECT – Highlighted -->
                <div class="bg-happylife-red bg-opacity-10 p-3 rounded-3 mb-4 d-flex align-items-center">
                    <div class="bg-happylife-red rounded-circle p-2 me-3">
                        <i class="bi bi-box-seam fs-3 text-white"></i>
                    </div>
                    <div>
                        <span class="badge bg-happylife-red mb-1">PRODUCT TO COLLECT</span>
                        <h4 class="fw-bold text-happylife-dark mb-0">{{ $claim->product->name }}</h4>
                        <small class="text-secondary d-block mt-1">
                            <strong>Entitlement:</strong> 
                            {{ $claim->product->package->product_entitlement ?? '—' }}
                        </small>
                        <small class="text-secondary">PV: {{ $claim->product->pv }} · Retail: ₦{{ number_format($claim->product->price, 2) }}</small>
                    </div>
                </div>

                <!-- Claim & Member Info -->
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-secondary d-block">Claim Number</small>
                        <span class="fw-bold">{{ $claim->claim_number }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Date Claimed</small>
                        <span class="fw-bold">{{ $claim->claimed_at->format('F d, Y') }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Member Name</small>
                        <span class="fw-bold">{{ $claim->user->name }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Username</small>
                        <span class="fw-bold">{{ $claim->user->username }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Email</small>
                        <span>{{ $claim->user->email }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Phone</small>
                        <span>{{ $claim->user->phone ?? '—' }}</span>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold text-happylife-dark mb-3">
                    <i class="bi bi-geo-alt-fill text-happylife-red me-2"></i>Pickup Information
                </h5>
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-secondary d-block">Pickup Center</small>
                        <span class="fw-bold">{{ $claim->pickupCenter->name }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-secondary d-block">Address</small>
                        <span>{{ $claim->pickupCenter->address }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Contact Person</small>
                        <span>{{ $claim->pickupCenter->contact_person }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Contact Phone</small>
                        <span>{{ $claim->pickupCenter->contact_phone }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-secondary d-block">Operating Hours</small>
                        <span>{{ $claim->pickupCenter->operating_hours }}</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Receipt footer -->
                <div class="text-center text-muted small">
                    <p>This receipt must be presented at the pickup center to collect your product.</p>
                    <p class="mb-0">Generated on {{ now()->format('F d, Y h:i A') }}</p>
                </div>

                <div class="border-top pt-3 mt-3 text-center no-print">
                    <span class="badge bg-happylife-light text-dark">Keep this receipt – you'll need it for collection</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background-color: white;
        }
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        #receipt {
            margin: 0;
            padding: 1rem;
        }
    }
</style>
@endsection