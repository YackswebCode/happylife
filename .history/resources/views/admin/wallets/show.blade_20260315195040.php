@extends('layouts.admin')

@section('title', 'Wallet Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Wallet #{{ $wallet->id }}</h2>
        <a href="{{ route('admin.wallets.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Wallet Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">User:</th>
                            <td>{{ $wallet->user->name ?? 'Deleted' }} ({{ $wallet->user->email ?? '' }})</td>
                        </tr>
                        <tr>
                            <th>Wallet Type:</th>
                            <td>{{ ucfirst($wallet->type) }}</td>
                        </tr>
                        <tr>
                            <th>Balance:</th>
                            <td>₦{{ number_format($wallet->balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Locked Balance:</th>
                            <td>₦{{ number_format($wallet->locked_balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $wallet->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $wallet->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection