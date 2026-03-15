@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::guard('admin')->user()->name }}!</p>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection