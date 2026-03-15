<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status');

        $transactions = WalletTransaction::with(['user', 'wallet'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.wallet_transactions.index', compact('transactions'));
    }

    public function show(WalletTransaction $walletTransaction)
    {
        $walletTransaction->load(['user', 'wallet']);
        return view('admin.wallet_transactions.show', compact('walletTransaction'));
    }
}