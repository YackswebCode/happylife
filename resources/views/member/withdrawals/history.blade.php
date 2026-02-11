@extends('layouts.member')

@section('title', 'Withdrawal History - Happylife')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-happylife-dark">Withdrawal History</h1>
            <p class="text-secondary">All your payout requests</p>
        </div>
        <div>
            <a href="{{ route('member.withdraw.index') }}" class="btn btn-happylife-red me-2">
                <i class="bi bi-plus-circle me-2"></i> New Request
            </a>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card product-card p-4">
        @if($withdrawals->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Fee (2%)</th>
                            <th>Net Amount</th>
                            <th>Bank</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $wd)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $wd->reference ?? '—' }}</span>
                                </td>
                                <td>
                                    {{ $wd->created_at->format('M d, Y') }}
                                    <small class="d-block text-secondary">{{ $wd->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="fw-bold">₦{{ number_format($wd->amount, 2) }}</td>
                                <td class="text-danger">₦{{ number_format($wd->fee, 2) }}</td>
                                <td class="fw-bold text-happylife-red">₦{{ number_format($wd->net_amount, 2) }}</td>
                                <td>
                                    @php $bank = $wd->bank_details; @endphp
                                    @if($bank)
                                        {{ $bank['bank_name'] ?? '' }}<br>
                                        <small class="text-secondary">...{{ substr($bank['account_number'] ?? '', -4) }}</small>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($wd->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($wd->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($wd->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($wd->status == 'cancelled')
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($wd->status == 'pending')
                                        <form action="{{ route('member.withdraw.cancel', $wd->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this withdrawal request?')">
                                                <i class="bi bi-x-circle"></i> Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $withdrawals->links() }}
            </div>
        @else
            <div class="text-center py-5 text-secondary">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
                <h4 class="mt-3">No withdrawal requests</h4>
                <p>You haven't made any withdrawal requests yet.</p>
                <a href="{{ route('member.withdraw.index') }}" class="btn btn-happylife-red mt-3 px-5 py-3">
                    <i class="bi bi-cash-stack me-2"></i> Request Withdrawal
                </a>
            </div>
        @endif
    </div>
</div>
@endsection