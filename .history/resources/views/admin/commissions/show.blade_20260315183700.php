@extends('layouts.admin')

@section('title', 'Commission Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Commission #{{ $commission->id }}</h2>
        <a href="{{ route('admin.commissions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Commission Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">User:</th>
                            <td>{{ $commission->user->name ?? 'Deleted' }} ({{ $commission->user->email ?? '' }})</td>
                        </tr>
                        <tr>
                            <th>From User:</th>
                            <td>{{ $commission->fromUser->name ?? 'Deleted' }} ({{ $commission->fromUser->email ?? '' }})</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td><span class="badge bg-info">{{ ucfirst($commission->type) }}</span></td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>₦{{ number_format($commission->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $commission->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>From Package ID:</th>
                            <td>{{ $commission->from_package_id ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($commission->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($commission->status ?? 'pending') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $commission->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $commission->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection