@extends('layouts.admin')

@section('title', 'Manage Packages')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Packages</h2>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New Package
        </a>
    </div>

    <!-- Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.packages.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Packages Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>PV</th>
                        <th>Direct Bonus</th>
                        <th>Indirect Bonus</th>
                        <th>Upgrade Bonus</th>
                        <th>Pairing Cap</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                    <tr>
                        <td>#{{ $package->id }}</td>
                        <td>{{ $package->name }}</td>
                        <td>₦{{ number_format($package->price, 2) }}</td>
                        <td>{{ $package->pv }}</td>
                        <td>₦{{ number_format($package->direct_bonus_amount, 2) }}</td>
                        <td>₦{{ number_format($package->indirect_bonus_amount, 2) }}</td>
                        <td>₦{{ number_format($package->upgrade_bonus_amount, 2) }}</td>
                        <td>₦{{ number_format($package->pairing_cap, 2) }}</td>
                        <td>{{ $package->order }}</td>
                        <td>
                            @if($package->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.packages.show', $package) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this package? This cannot be undone if users are assigned.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="11" class="text-center py-4 text-muted">No packages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($packages->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $packages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection