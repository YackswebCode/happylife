@extends('layouts.admin')

@section('title', 'KYC Verifications')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">KYC Verifications</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.kyc.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by user name, email or username..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- KYC Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Document Type</th>
                        <th>ID Number</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kycs as $kyc)
                    <tr>
                        <td>#{{ $kyc->id }}</td>
                        <td>
                            <div>
                                <div>{{ $kyc->user->name ?? 'Deleted User' }}</div>
                                <small class="text-muted">{{ $kyc->user->email ?? '' }}</small>
                            </div>
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $kyc->document_type)) }}</td>
                        <td>{{ $kyc->id_number ?? '—' }}</td>
                        <td>{{ $kyc->created_at->format('d M Y') }}</td>
                        <td>
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                ][$kyc->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($kyc->status) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.kyc.show', $kyc) }}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No KYC submissions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kycs->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $kycs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection