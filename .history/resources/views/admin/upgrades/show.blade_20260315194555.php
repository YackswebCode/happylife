@extends('layouts.admin')

@section('title', 'Upgrade Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Upgrade #{{ $upgrade->id }}</h2>
        <a href="{{ route('admin.upgrades.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Upgrade Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">User:</th>
                            <td>{{ $upgrade->user->name ?? 'Deleted' }} ({{ $upgrade->user->email ?? '' }})</td>
                        </tr>
                        <tr>
                            <th>From Package:</th>
                            <td>{{ $upgrade->oldPackage->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>To Package:</th>
                            <td>{{ $upgrade->newPackage->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Difference Amount:</th>
                            <td>₦{{ number_format($upgrade->difference_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $upgrade->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th>Reference:</th>
                            <td><code>{{ $upgrade->reference ?? '—' }}</code></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
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
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $upgrade->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $upgrade->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection