@extends('layouts.member')

@section('title', 'Commission Wallet - Happylife')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <a href="{{ route('member.wallet.index') }}" class="text-teal-blue text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Back to Wallets
        </a>
    </div>

    <!-- Wallet Header -->
    <div class="bg-teal-blue text-white rounded-4 p-4 mb-4 shadow">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="small opacity-75 mb-1">Commission Wallet</p>
                <h1 class="display-6 fw-bold">₦{{ number_format($wallet->balance, 2) }}</h1>
                <span class="badge bg-white text-dark bg-opacity-25">Locked: ₦{{ number_format($wallet->locked_balance, 2) }}</span>
            </div>
            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                <i class="bi bi-cash-stack fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 text-dark-gray"><i class="bi bi-clock-history me-2 text-red"></i> Transaction History</h5>
        </div>
        <div class="card-body">
            @if($transactions->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Description</th>
                                <th>Reference</th>
                                <th>Date</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                <tr>
                                    <td>{{ $tx->description }}</td>
                                    <td><small class="text-muted">{{ $tx->reference }}</small></td>
                                    <td><small>{{ $tx->created_at->format('M d, Y h:i A') }}</small></td>
                                    <td class="text-end fw-bold {{ $tx->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                        {{ $tx->formatted_amount }}
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($tx->status == 'completed') bg-success
                                            @elseif($tx->status == 'pending') bg-warning text-dark
                                            @else bg-secondary @endif">
                                            {{ ucfirst($tx->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-receipt display-1"></i>
                    <p class="mt-3 fs-5">No transactions yet.</p>
                    <p class="small">Your commission earnings will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection