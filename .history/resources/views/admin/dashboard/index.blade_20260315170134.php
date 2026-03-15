@extends('layouts.admin')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Dashboard</h2>
        <div>
            <span class="badge bg-light text-dark p-2">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Users -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Total Users</span>
                            <h3 class="stat-value mt-2">{{ number_format($totalUsers) }}</h3>
                            <small class="text-success"><i class="bi bi-arrow-up"></i> {{ number_format($activeUsers) }} active</small>
                        </div>
                        <div class="stat-icon text-teal-blue">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending KYC -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Pending KYC</span>
                            <h3 class="stat-value mt-2">{{ number_format($pendingKyc) }}</h3>
                            <small class="text-warning">awaiting verification</small>
                        </div>
                        <div class="stat-icon text-warning">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Pending Withdrawals</span>
                            <h3 class="stat-value mt-2">{{ number_format($pendingWithdrawals) }}</h3>
                            <small class="text-danger">needs attention</small>
                        </div>
                        <div class="stat-icon text-red">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Funding -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Funding Requests</span>
                            <h3 class="stat-value mt-2">{{ number_format($pendingFunding) }}</h3>
                            <small class="text-info">awaiting approval</small>
                        </div>
                        <div class="stat-icon text-soft-cyan">
                            <i class="bi bi-piggy-bank-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row g-4 mb-4">
        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Total Orders</span>
                            <h3 class="stat-value mt-2">{{ number_format($totalOrders) }}</h3>
                            <small class="text-warning">{{ number_format($pendingOrders) }} pending</small>
                        </div>
                        <div class="stat-icon text-dark-gray">
                            <i class="bi bi-cart-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Total Sales</span>
                            <h3 class="stat-value mt-2">{{ number_format($totalSales, 2) }}</h3>
                            <small class="text-success">paid orders</small>
                        </div>
                        <div class="stat-icon text-success">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Commissions Paid -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Commissions Paid</span>
                            <h3 class="stat-value mt-2">{{ number_format($totalCommissionsPaid, 2) }}</h3>
                            <small class="text-teal-blue">all time</small>
                        </div>
                        <div class="stat-icon text-teal-blue">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users (already counted) -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Active Users</span>
                            <h3 class="stat-value mt-2">{{ number_format($activeUsers) }}</h3>
                            <small class="text-muted">{{ round(($activeUsers / max($totalUsers,1)) * 100) }}% of total</small>
                        </div>
                        <div class="stat-icon text-success">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Row -->
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-red"></i>Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td><span class="badge bg-light text-dark">{{ $order->order_number }}</span></td>
                                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($order->total, 2) }}</td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ][$order->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                            </td>
                                            <td><small>{{ $order->created_at->diffForHumans() }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-2">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-red">View All Orders <i class="bi bi-arrow-right ms-1"></i></a>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent orders</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-plus-fill me-2 text-teal-blue"></i>New Users</h5>
                </div>
                <div class="card-body">
                    @if($recentUsers->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td><code>{{ $user->username }}</code></td>
                                            <td>{{ $user->email }}</td>
                                            <td><small>{{ $user->created_at->diffForHumans() }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-2">
                            <a href="" class="btn btn-sm btn-outline-teal-blue">View All Users <i class="bi bi-arrow-right ms-1"></i></a>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent users</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection