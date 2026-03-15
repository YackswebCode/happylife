<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundingRequest;
use Illuminate\Http\Request;

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
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($payment_method, function ($query, $payment_method) {
                return $query->where('payment_method', $payment_method);
            })
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
     */
    public function update(Request $request, FundingRequest $fundingRequest)
    {
        $request->validate([
            'status'       => 'required|in:pending,approved,rejected',
            'admin_notes'  => 'nullable|string|max:1000',
        ]);

        $data = [
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        // If approved, set approved_at timestamp
        if ($request->status == 'approved' && $fundingRequest->status != 'approved') {
            $data['approved_at'] = now();
        }

        $fundingRequest->update($data);

        // If approved, you might want to credit the user's wallet here
        // This could be done via an event listener or directly in the controller
        // For now, we just update status

        return redirect()->route('admin.funding-requests.index')
            ->with('success', 'Funding request status updated successfully.');
    }
}