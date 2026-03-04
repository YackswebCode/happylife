@extends('layouts.member')

@section('title', 'Cable TV - Happylife')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('member.vtu.index') }}" class="btn btn-outline-happylife-teal me-3">
            <i class="bi bi-arrow-left me-1"></i> Back to VTU
        </a>
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Cable TV Subscription</h1>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card product-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-happylife-cyan bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-tv fs-3 text-happylife-cyan"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Subscribe to Cable TV</h5>
                        <p class="text-secondary mb-0">DSTV, GOTV, Startimes</p>
                    </div>
                </div>

                <form action="{{ route('member.vtu.cable.purchase') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Provider</label>
                            <select name="provider" id="provider" class="form-select" required>
                                <option value="">Select Provider</option>
                                @foreach($cableProviders ?? [] as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Smart Card / IUC Number</label>
                            <input type="text" name="smart_card" class="form-control" placeholder="e.g. 1234567890" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bouquet / Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select" required>
                                <option value="">First select a provider</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Amount (₦)</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" name="amount" id="amount" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Your Commission Wallet</label>
                            <div class="form-control bg-light" readonly>
                                ₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-happylife-cyan btn-lg w-100 py-3">
                                <i class="bi bi-lightning-charge me-2"></i> Subscribe
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card product-card p-4 mb-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-wallet2 me-2"></i> Wallet Balance
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Commission Wallet:</span>
                    <span class="fw-bold text-happylife-red">₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}</span>
                </div>
            </div>
            <div class="card product-card p-4">
                <h5 class="fw-bold text-happylife-dark border-bottom pb-3">
                    <i class="bi bi-clock-history me-2"></i> Recent Subscriptions
                </h5>
                @if(isset($recentCable) && $recentCable->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentCable as $tx)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="fw-bold">{{ $tx->provider_name }}</small>
                                        <br>
                                        <small class="text-secondary">{{ $tx->smart_card }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-happylife-red">₦{{ number_format($tx->amount, 2) }}</span>
                                        <br>
                                        <span class="badge bg-success">Success</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary text-center py-3 mb-0">No recent subscriptions.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('provider').addEventListener('change', function() {
        const providerId = this.value;
        const planSelect = document.getElementById('plan_id');
        const amountInput = document.getElementById('amount');

        planSelect.innerHTML = '<option value="">Loading bouquets...</option>';

        if (!providerId) {
            planSelect.innerHTML = '<option value="">First select a provider</option>';
            return;
        }

        fetch(`/vtu/cable-plans?provider_id=${providerId}`)
            .then(response => response.json())
            .then(plans => {
                planSelect.innerHTML = '<option value="">Select bouquet</option>';
                plans.forEach(plan => {
                    const option = document.createElement('option');
                    option.value = plan.id;
                    option.textContent = `${plan.name} - ₦${plan.amount}`;
                    option.dataset.amount = plan.amount;
                    planSelect.appendChild(option);
                });
            });
    });

    document.getElementById('plan_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const amount = selected.dataset.amount || 0;
        document.getElementById('amount').value = amount;
    });
</script>
@endpush
@endsection