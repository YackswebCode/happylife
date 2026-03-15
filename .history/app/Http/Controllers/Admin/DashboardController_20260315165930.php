<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Kyc;
use App\Models\Withdrawal;
use App\Models\FundingRequest;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $pendingKyc = Kyc::where('status', 'pending')->count();
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $pendingFunding = FundingRequest::where('status', 'pending')->count();

        // Orders summary
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalSales = Order::where('payment_status', 'paid')->sum('total');

        // Commissions summary
        $totalCommissionsPaid = Commission::where('status', 'paid')->sum('amount');

        // Recent activities (e.g., latest 5 orders, latest 5 registrations)
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalUsers',
            'activeUsers',
            'pendingKyc',
            'pendingWithdrawals',
            'pendingFunding',
            'totalOrders',
            'pendingOrders',
            'totalSales',
            'totalCommissionsPaid',
            'recentOrders',
            'recentUsers'
        ));
    }
}