@extends('layouts.admin')

@section('title', 'Rank Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Rank: {{ $rank->name }}</h2>
        <div>
            <a href="{{ route('admin.ranks.edit', $rank) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.ranks.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Rank Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Name:</th>
                            <td>{{ $rank->name }}</td>
                        </tr>
                        <tr>
                            <th>Level:</th>
                            <td>{{ $rank->level }}</td>
                        </tr>
                        <tr>
                            <th>Required PV:</th>
                            <td>{{ number_format($rank->required_pv, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Cash Reward:</th>
                            <td>₦{{ number_format($rank->cash_reward, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Other Reward:</th>
                            <td>{{ $rank->other_reward ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $rank->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($rank->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $rank->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $rank->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection