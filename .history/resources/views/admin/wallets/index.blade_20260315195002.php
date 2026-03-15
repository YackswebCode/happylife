@extends('layouts.admin')

@section('title', 'User Wallets')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">User Wallets</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.wallets.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search user..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">All Wallet Types</option>
                            <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>Commission</option>
                            <option value="registration" {{ request('type') == 'registration' ? 'selected' : '' }}>Registration</option>
                            <option value="shopping" {{ request('type') == 'shopping' ? 'selected' : '' }}>Shopping</option>
                            <option value="rank" {{ request('type') == 'rank' ? 'selected' : '' }}>Rank</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Wallets Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Wallet Type</th>
                        <th>Balance</th>
                        <th>Locked</th>
                        <th>Last Updated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wallets as $wallet)
                    <tr>
                        <td>#{{ $wallet->id }}</td>
                        <td>{{ $wallet->user->name ?? 'Deleted' }}</td>
                        <td>{{ ucfirst($wallet->type) }}</td>
                        <td>₦{{ number_format($wallet->balance, 2) }}</td>
                        <td>₦{{ number_format($wallet->locked_balance, 2) }}</td>
                        <td>{{ $wallet->updated_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.wallets.show', $wallet) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No wallets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($wallets->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $wallets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection