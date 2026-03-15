@extends('layouts.admin')

@section('title', 'Product Claims')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Product Claims</h2>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.product-claims.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by user or claim #" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="collected" {{ request('status')=='collected' ? 'selected' : '' }}>Collected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Claim #</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Claimed At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                    <tr>
                        <td><span class="badge bg-light text-dark">{{ $claim->claim_number }}</span></td>
                        <td>{{ $claim->user->name ?? 'Deleted' }}</td>
                        <td>{{ $claim->product->name ?? 'Deleted' }}</td>
                        <td>
                            @php
                                $class = match($claim->status) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'collected' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $class }}">{{ ucfirst($claim->status) }}</span>
                        </td>
                        <td>{{ $claim->claimed_at ? $claim->claimed_at->format('d M Y') : '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.product-claims.show', $claim) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.product-claims.edit', $claim) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">No claims found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($claims->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $claims->links() }}
        </div>
        @endif
    </div>
</div>
@endsection