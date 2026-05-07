<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundingRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundingRequestController extends Controller
{
    /**
     * Display a listing of funding requests.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $payment_method = $request->input('payment_method');

        $fundings = FundingRequest::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('transaction_id', 'like', "%{$search}%");
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($payment_method, fn($q) => $q->where('payment_method', $payment_method))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.funding_requests.index', compact('fundings'));
    }

    /**
     * Display the specified funding request.
     */
    public function show(FundingRequest $fundingRequest)
    {
        $fundingRequest->load('user');
        return view('admin.funding_requests.show', compact('fundingRequest'));
    }

    /**
     * Update the status of the funding request.
     * When approved, credit the user's shopping wallet.
     */
    public function update(Request $request, FundingRequest $fundingRequest)
    {
        $request->validate([
            'status'      => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $fundingRequest->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($fundingRequest, $newStatus, $oldStatus, $request) {
            // Prepare update data
            $data = [
                'status'      => $newStatus,
                'admin_notes' => $request->admin_notes,
            ];

            // If newly approved
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                $data['approved_at'] = now();

                // Credit the user's shopping wallet
                $wallet = Wallet::firstOrCreate(
                    [
                        'user_id' => $fundingRequest->user_id,
                        'type'    => 'shopping',   // you can change this to whatever wallet type you need
                    ],
                    [
                        'balance'        => 0.00,
                        'locked_balance' => 0.00,
                    ]
                );

                $wallet->increment('balance', $fundingRequest->amount);
            }

            $fundingRequest->update($data);
        });

        return redirect()->route('admin.funding-requests.index')
            ->with('success', 'Funding request status updated successfully.');
    }
}