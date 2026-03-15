@extends('layouts.admin')

@section('title', 'Package Upgrades')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Package Upgrades</h2>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.upgrades.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search user or reference..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Upgrades Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>From Package</th>
                            <th>To Package</th>
                            <th>Difference</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upgrades as $upgrade)
                        <tr>
                            <td>#{{ $upgrade->id }}</td>
                            <td>{{ $upgrade->user->name ?? 'Deleted' }}</td>
                            <td>{{ $upgrade->oldPackage->name ?? '—' }}</td>
                            <td>{{ $upgrade->newPackage->name ?? '—' }}</td>
                            <td>₦{{ number_format($upgrade->difference_amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $upgrade->payment_method)) }}</td>
                            <td><code>{{ $upgrade->reference ?? '—' }}</code></td>
                            <td>
                                @php
                                    $statusClass = [
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                    ][$upgrade->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($upgrade->status) }}</span>
                            </td>
                            <td>{{ $upgrade->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.upgrades.show', $upgrade) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center py-4 text-muted">No upgrades found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($upgrades->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $upgrades->links() }}
        </div>
        @endif
    </div>
</div>
@endsection