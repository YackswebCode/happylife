@extends('layouts.member')

@section('title', 'VTU Services - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header with wallet balance -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-happylife-dark">VTU Services</h1>
            <p class="text-secondary">Buy airtime, data, pay bills instantly</p>
        </div>
        <a href="{{ route('member.wallet.commission') }}" class="btn btn-outline-happylife-teal d-flex align-items-center">
            <i class="bi bi-wallet2 me-2"></i> Commission Wallet: 
            <span class="fw-bold ms-1">₦{{ number_format(auth()->user()->commission_wallet_balance ?? 0, 2) }}</span>
        </a>
    </div>

    <!-- Service Cards Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('member.vtu.airtime') }}" class="text-decoration-none">
                <div class="card product-card h-100 p-4 text-center">
                    <div class="bg-happylife-red bg-opacity-10 rounded-circle d-inline-flex p-3 mx-auto mb-3">
                        <i class="bi bi-phone-fill fs-1 text-happylife-red"></i>
                    </div>
                    <h5 class="fw-bold text-happylife-dark">Airtime</h5>
                    <p class="text-secondary small">MTN, Glo, Airtel, 9mobile</p>
                    <span class="btn btn-sm btn-happylife-red mt-2">Buy Now</span>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('member.vtu.data') }}" class="text-decoration-none">
                <div class="card product-card h-100 p-4 text-center">
                    <div class="bg-happylife-teal bg-opacity-10 rounded-circle d-inline-flex p-3 mx-auto mb-3">
                        <i class="bi bi-wifi fs-1 text-happylife-teal"></i>
                    </div>
                    <h5 class="fw-bold text-happylife-dark">Data Bundle</h5>
                    <p class="text-secondary small">Daily, weekly, monthly plans</p>
                    <span class="btn btn-sm btn-happylife-teal mt-2">Buy Now</span>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('member.vtu.cable') }}" class="text-decoration-none">
                <div class="card product-card h-100 p-4 text-center">
                    <div class="bg-happylife-cyan bg-opacity-10 rounded-circle d-inline-flex p-3 mx-auto mb-3">
                        <i class="bi bi-tv fs-1 text-happylife-cyan"></i>
                    </div>
                    <h5 class="fw-bold text-happylife-dark">Cable TV</h5>
                    <p class="text-secondary small">DSTV, GOTV, Startimes</p>
                    <span class="btn btn-sm btn-happylife-cyan mt-2">Subscribe</span>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('member.vtu.electricity') }}" class="text-decoration-none">
                <div class="card product-card h-100 p-4 text-center">
                    <div class="bg-happylife-dark bg-opacity-10 rounded-circle d-inline-flex p-3 mx-auto mb-3">
                        <i class="bi bi-lightbulb fs-1 text-happylife-dark"></i>
                    </div>
                    <h5 class="fw-bold text-happylife-dark">Electricity</h5>
                    <p class="text-secondary small">Prepaid & postpaid bills</p>
                    <span class="btn btn-sm btn-happylife-dark mt-2">Pay Bill</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent VTU Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card product-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-happylife-dark mb-0">
                        <i class="bi bi-clock-history me-2"></i> Recent Transactions
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-happylife-teal">View All</a>
                </div>
                @if(isset($recentTransactions) && $recentTransactions->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Service</th>
                                    <th>Details</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $tx)
                                    <tr>
                                        <td>
                                            <span class="badge bg-happylife-teal">{{ $tx->service_type }}</span>
                                        </td>
                                        <td>{{ $tx->description }}</td>
                                        <td class="fw-bold text-happylife-red">₦{{ number_format($tx->amount, 2) }}</td>
                                        <td>
                                            @if($tx->status == 'success')
                                                <span class="badge bg-success">Success</span>
                                            @elseif($tx->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $tx->created_at->format('M d, h:i A') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-secondary">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>No VTU transactions yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection