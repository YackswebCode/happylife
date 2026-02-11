@extends('layouts.member')

@section('content')
<div class="container py-5">
    <div class="alert alert-warning">
        <h4>No Product to Claim</h4>
        <p>{{ $message }}</p>
        <a href="{{ route('member.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
    </div>
</div>
@endsection