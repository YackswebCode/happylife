<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\BankSetting;
use Illuminate\Http\Request;
use App\Models\User;

class PaymentController extends Controller
{
    /**
     * Display payments and bank settings.
     */
    public function index(Request $request)
    {
        $bank = BankSetting::first(); // load bank settings

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
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($method, fn($query) => $query->where('payment_method', $method))
            ->when($from_date, fn($query) => $query->whereDate('created_at', '>=', $from_date))
            ->when($to_date, fn($query) => $query->whereDate('created_at', '<=', $to_date))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', compact('payments', 'bank'));
    }

    /**
     * Update or create bank settings.
     */
    public function updateBankSettings(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:255',
        ]);

        BankSetting::updateOrCreate(
            ['id' => 1],
            $request->only('bank_name', 'account_number', 'account_name')
        );

        return back()->with('success', 'Bank details updated successfully.');
    }

    /**
     * Show a specific payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'package']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Update payment status.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $payment->status = $request->status;
        $payment->save();

        $user = $payment->user;
        if ($user) {
            if ($request->status === 'paid') {
                $user->payment_status = 'paid';
                $user->activated_at = now();
                $user->status = 'active';
                $user->save();
            } elseif ($request->status === 'cancelled') {
                $user->payment_status = 'unpaid';
                $user->save();
            }
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment status updated successfully.');
    }
}