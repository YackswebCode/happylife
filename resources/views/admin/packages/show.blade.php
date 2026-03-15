@extends('layouts.admin')

@section('title', 'Package Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Package: {{ $package->name }}</h2>
        <div>
            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Packages
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Package Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">Name:</th>
                            <td>{{ $package->name }}</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>₦{{ number_format($package->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>PV:</th>
                            <td>{{ $package->pv }}</td>
                        </tr>
                        <tr>
                            <th>Product Entitlement:</th>
                            <td>{{ $package->product_entitlement }}</td>
                        </tr>
                        <tr>
                            <th>Direct Bonus:</th>
                            <td>₦{{ number_format($package->direct_bonus_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Indirect Bonus:</th>
                            <td>₦{{ number_format($package->indirect_bonus_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Upgrade Bonus:</th>
                            <td>₦{{ number_format($package->upgrade_bonus_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Pairing Cap:</th>
                            <td>₦{{ number_format($package->pairing_cap, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $package->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Display Order:</th>
                            <td>{{ $package->order }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($package->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $package->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $package->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection