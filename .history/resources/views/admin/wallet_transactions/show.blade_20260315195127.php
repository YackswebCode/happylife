@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Transaction #{{ $walletTransaction->id }}</h2>
        <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Transaction Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">User:</th>
                            <td>{{ $walletTransaction->user->name ?? 'Deleted' }} ({{ $walletTransaction->user->email ?? '' }})</td>
                        </tr>
                        <tr>
                            <th>Wallet:</th>
                            <td>{{ $walletTransaction->wallet ? ucfirst($walletTransaction->wallet->type) : '—' }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td><span class="badge bg-{{ $walletTransaction->type == 'credit' ? 'success' : ($walletTransaction->type == 'debit' ? 'danger' : 'info') }}">{{ ucfirst($walletTransaction->type) }}</span></td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>₦{{ number_format($walletTransaction->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $walletTransaction->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Reference:</th>
                            <td><code>{{ $walletTransaction->reference }}</code></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                    ][$walletTransaction->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($walletTransaction->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $walletTransaction->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $walletTransaction->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>

                    @if($walletTransaction->metadata)
                    <hr>
                    <h6 class="fw-bold">Metadata</h6>
                    <pre class="bg-light p-3 rounded">{{ json_encode($walletTransaction->metadata, JSON_PRETTY_PRINT) }}</pre>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection