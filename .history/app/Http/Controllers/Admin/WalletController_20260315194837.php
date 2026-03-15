<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $wallets = Wallet::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.wallets.index', compact('wallets'));
    }

    public function show(Wallet $wallet)
    {
        $wallet->load('user');
        return view('admin.wallets.show', compact('wallet'));
    }
}