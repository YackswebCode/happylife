<?php

namespace App\Http\Controllers\Admin\Vtu;

use App\Http\Controllers\Controller;
use App\Models\VtuTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $service_type = $request->input('service_type');
        $status = $request->input('status');

        $transactions = VtuTransaction::with(['user', 'provider', 'plan'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when($service_type, function ($query, $service_type) {
                return $query->where('service_type', $service_type);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.vtu.transactions.index', compact('transactions'));
    }

    public function show(VtuTransaction $transaction)
    {
        $transaction->load(['user', 'provider', 'plan']);
        return view('admin.vtu.transactions.show', compact('transaction'));
    }
}