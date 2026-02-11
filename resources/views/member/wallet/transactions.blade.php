@extends('layouts.member')

@section('title', 'All Transactions - Happylife')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark-gray">All Wallet Transactions</h1>
            <p class="text-muted">Complete history across all wallets</p>
        </div>
        <a href="{{ route('member.wallet.index') }}" class="btn btn-teal-blue rounded-pill px-4 py-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Wallets
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            @if($transactions->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Wallet</th>
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
                                    <td>
                                        <span class="badge 
                                            @if($tx->wallet->type == 'commission') bg-teal-blue
                                            @elseif($tx->wallet->type == 'rank') bg-red
                                            @elseif($tx->wallet->type == 'shopping') bg-dark-gray
                                            @else bg-soft-cyan @endif 
                                            text-white text-capitalize">
                                            {{ $tx->wallet->type }}
                                        </span>
                                    </td>
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
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-receipt display-1"></i>
                    <p class="mt-3 fs-5">No transactions found.</p>
                    <p class="small">Your transaction history will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection