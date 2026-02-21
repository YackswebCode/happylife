@extends('layouts.member')

@section('title', 'Matching Bonus - Happylife Multipurpose Int\'l')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark-gray">Matching / Pairing Bonus</h1>
            <p class="text-muted">Earn bonuses when your left and right teams match PV.</p>
        </div>
    </div>

    <!-- PV Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-light-red h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="small text-muted mb-1">Left Team PV</p>
                            <h3 class="fw-bold text-red">{{ number_format($user->left_pv ?? 0, 1) }}</h3>
                        </div>
                        <div class="bg-red bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-arrow-left-circle-fill fs-3 text-red"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-light-teal h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="small text-muted mb-1">Right Team PV</p>
                            <h3 class="fw-bold text-teal-blue">{{ number_format($user->right_pv ?? 0, 1) }}</h3>
                        </div>
                        <div class="bg-teal-blue bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-arrow-right-circle-fill fs-3 text-teal-blue"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-light-purple h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="small text-muted mb-1">Weaker Side PV</p>
                            <h3 class="fw-bold text-purple">{{ number_format(min($user->left_pv ?? 0, $user->right_pv ?? 0), 1) }}</h3>
                        </div>
                        <div class="bg-purple bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-graph-down fs-3 text-purple"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-light-success h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="small text-muted mb-1">Total Matching Bonus</p>
                            <h3 class="fw-bold text-success">₦{{ number_format($totalMatchingBonus, 2) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-cash-stack fs-3 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How Matching Works -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-dark-gray"><i class="bi bi-info-circle-fill text-teal-blue me-2"></i> How Matching Bonus Works</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-soft-cyan bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-1-circle-fill fs-3 text-teal-blue"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Pair Formation</h6>
                                    <p class="text-muted small mb-0">Every time your left and right teams generate PV, pairs are formed based on the weaker side.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-soft-cyan bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-calculator-fill fs-3 text-teal-blue"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Bonus Calculation</h6>
                                    <p class="text-muted small mb-0">You earn a percentage (e.g., 10%) of the weaker side's PV as matching bonus.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-soft-cyan bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-wallet2 fs-3 text-teal-blue"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Paid to Commission Wallet</h6>
                                    <p class="text-muted small mb-0">Bonuses are automatically credited to your commission wallet.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Matching Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-dark-gray"><i class="bi bi-clock-history text-red me-2"></i> Recent Matching Bonuses</h5>
                </div>
                <div class="card-body p-4">
                    @if($recentTransactions->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $tx)
                                        <tr>
                                            <td><small>{{ $tx->created_at->format('M d, Y h:i A') }}</small></td>
                                            <td>{{ $tx->description }}</td>
                                            <td class="text-end fw-bold text-success">₦{{ number_format($tx->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-cash-stack display-4"></i>
                            <p class="mt-3">No matching bonuses earned yet.</p>
                            <p class="small">Start building your network to earn matching bonuses!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection