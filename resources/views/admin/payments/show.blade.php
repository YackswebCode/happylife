@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Payment #{{ $payment->id }}</h2>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">User:</th>
                            <td>{{ $payment->user->name ?? 'Deleted' }} ({{ $payment->user->email ?? '' }})</td>
                        </tr>
                        <tr>
                            <th>Package:</th>
                            <td>{{ $payment->package->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>₦{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th>Reference:</th>
                            <td><code>{{ $payment->reference ?? '—' }}</code></td>
                        </tr>
                        <tr>
                            <th>Gateway Reference:</th>
                            <td><code>{{ $payment->gateway_reference ?? '—' }}</code></td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $payment->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary',
                                        'refunded' => 'info',
                                    ][$payment->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $payment->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($payment->proof_url)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Proof of Payment</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/'.$payment->proof_url) }}" alt="Proof" class="img-fluid rounded" style="max-height: 400px;">
                </div>
            </div>
            @endif

            @if($payment->gateway_response)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Gateway Response</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow: auto;">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="cancelled" {{ $payment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="refunded" {{ $payment->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-red">Update Status</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection