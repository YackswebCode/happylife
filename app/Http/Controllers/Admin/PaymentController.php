<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $method = $request->input('payment_method');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $payments = Payment::with(['user', 'package'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('gateway_reference', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($method, function ($query, $method) {
                return $query->where('payment_method', $method);
            })
            ->when($from_date, function ($query, $from_date) {
                return $query->whereDate('created_at', '>=', $from_date);
            })
            ->when($to_date, function ($query, $to_date) {
                return $query->whereDate('created_at', '<=', $to_date);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'package']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Update the status of the payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled,refunded',
        ]);

        $payment->update(['status' => $request->status]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment status updated successfully.');
    }
}