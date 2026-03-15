<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawal requests.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $wallet_type = $request->input('wallet_type');

        $withdrawals = Withdrawal::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($wallet_type, function ($query, $wallet_type) {
                return $query->where('wallet_type', $wallet_type);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Display the specified withdrawal.
     */
    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load('user');
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Update the status of the withdrawal.
     */
    public function update(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $data = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        // If status is processed or approved, set processed_at
        if (in_array($request->status, ['processed', 'approved']) && $withdrawal->status != $request->status) {
            $data['processed_at'] = now();
        }

        $withdrawal->update($data);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Withdrawal status updated successfully.');
    }
}