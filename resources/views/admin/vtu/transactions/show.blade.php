@extends('layouts.admin')

@section('title', 'VTU Transaction Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Transaction #{{ $vtuTransaction->id }}</h2>
        <a href="{{ route('admin.vtu.transactions.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Transaction Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th style="width:150px;">User:</th><td>{{ $vtuTransaction->user->name ?? 'Deleted' }} ({{ $vtuTransaction->user->email ?? '' }})</td></tr>
                        <tr><th>Service Type:</th><td>{{ ucfirst($vtuTransaction->service_type) }}</td></tr>
                        <tr><th>Provider:</th><td>{{ $vtuTransaction->provider->name ?? '—' }}</td></tr>
                        <tr><th>Plan:</th><td>{{ $vtuTransaction->plan->name ?? '—' }}</td></tr>
                        <tr><th>Phone / Smart Card / Meter:</th><td>{{ $vtuTransaction->phone ?? $vtuTransaction->smart_card ?? $vtuTransaction->meter_number ?? '—' }}</td></tr>
                        <tr><th>Amount:</th><td>₦{{ number_format($vtuTransaction->amount, 2) }}</td></tr>
                        <tr><th>Reference:</th><td><code>{{ $vtuTransaction->reference }}</code></td></tr>
                        <tr><th>Status:</th><td>
                            @php $class = ['pending'=>'warning','successful'=>'success','failed'=>'danger'][$vtuTransaction->status]??'secondary'; @endphp
                            <span class="badge bg-{{ $class }}">{{ ucfirst($vtuTransaction->status) }}</span>
                        </td></tr>
                        <tr><th>Description:</th><td>{{ $vtuTransaction->description ?? '—' }}</td></tr>
                        <tr><th>Date:</th><td>{{ $vtuTransaction->created_at->format('d M Y, H:i') }}</td></tr>
                    </table>
                    @if($vtuTransaction->api_response)
                        <hr>
                        <h6 class="fw-bold">API Response</h6>
                        <pre class="bg-light p-3 rounded">{{ json_encode($vtuTransaction->api_response, JSON_PRETTY_PRINT) }}</pre>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection