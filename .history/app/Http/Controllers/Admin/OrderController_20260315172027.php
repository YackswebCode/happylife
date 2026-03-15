<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $payment_status = $request->input('payment_status');

        $orders = Order::with('user')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($payment_status, function ($query, $payment_status) {
                return $query->where('payment_status', $payment_status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load('user'); // eager load user
        $items = json_decode($order->items, true) ?? [];
        return view('admin.orders.show', compact('order', 'items'));
    }
}