@extends('layouts.admin')

@section('title', 'Admin Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Admin: {{ $admin->name }}</h2>
        <div>
            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Edit</a>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>Name:</th><td>{{ $admin->name }}</td></tr>
                        <tr><th>Email:</th><td>{{ $admin->email }}</td></tr>
                       <tr>
                            <th>Role:</th>
                            <td>
                                @if($admin->role == 'super_admin')
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($admin->role == 'admin')
                                    <span class="badge bg-info">Admin</span>
                                @elseif($admin->role == 'vendor')
                                    <span class="badge bg-success">Vendor</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Created:</th><td>{{ $admin->created_at->format('d M Y, H:i') }}</td></tr>
                        <tr><th>Last Updated:</th><td>{{ $admin->updated_at->format('d M Y, H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection