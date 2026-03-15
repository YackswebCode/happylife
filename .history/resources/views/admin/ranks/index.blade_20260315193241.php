@extends('layouts.admin')

@section('title', 'Ranks')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Ranks</h2>
        <a href="{{ route('admin.ranks.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add New Rank
        </a>
    </div>

    <!-- Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ranks.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ranks Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Required PV</th>
                        <th>Cash Reward</th>
                        <th>Other Reward</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ranks as $rank)
                    <tr>
                        <td>#{{ $rank->id }}</td>
                        <td>{{ $rank->name }}</td>
                        <td>{{ $rank->level }}</td>
                        <td>{{ number_format($rank->required_pv, 2) }}</td>
                        <td>₦{{ number_format($rank->cash_reward, 2) }}</td>
                        <td>{{ $rank->other_reward ?? '—' }}</td>
                        <td>
                            @if($rank->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.ranks.show', $rank) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.ranks.edit', $rank) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.ranks.destroy', $rank) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this rank?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No ranks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ranks->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $ranks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection