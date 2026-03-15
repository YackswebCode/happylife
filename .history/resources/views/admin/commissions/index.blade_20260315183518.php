@extends('layouts.admin')

@section('title', 'Commissions')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Commissions</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.commissions.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search user, from user, description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="direct" {{ request('type') == 'direct' ? 'selected' : '' }}>Direct</option>
                            <option value="indirect" {{ request('type') == 'indirect' ? 'selected' : '' }}>Indirect</option>
                            <option value="matching" {{ request('type') == 'matching' ? 'selected' : '' }}>Matching</option>
                            <option value="rank" {{ request('type') == 'rank' ? 'selected' : '' }}>Rank</option>
                            <option value="repurchase" {{ request('type') == 'repurchase' ? 'selected' : '' }}>Repurchase</option>
                            <option value="lifestyle" {{ request('type') == 'lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                            <option value="upgrade" {{ request('type') == 'upgrade' ? 'selected' : '' }}>Upgrade</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from_date" class="form-control" placeholder="From Date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to_date" class="form-control" placeholder="To Date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>From User</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                        <tr>
                            <td>#{{ $commission->id }}</td>
                            <td>{{ $commission->user->name ?? 'Deleted' }}</td>
                            <td>{{ $commission->fromUser->name ?? 'Deleted' }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($commission->type) }}</span></td>
                            <td>₦{{ number_format($commission->amount, 2) }}</td>
                            <td>{{ Str::limit($commission->description, 50) }}</td>
                            <td>
                                @if($commission->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($commission->status ?? 'pending') }}</span>
                                @endif
                            </td>
                            <td>{{ $commission->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.commissions.show', $commission) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-4 text-muted">No commissions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($commissions->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection