<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $kycs = Kyc::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.kyc.index', compact('kycs'));
    }

    public function show(Kyc $kyc)
    {
        $kyc->load('user');
        return view('admin.kyc.show', compact('kyc'));
    }

    public function update(Request $request, Kyc $kyc)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'admin_comment' => 'nullable|string|max:1000',
        ]);

        $data = [
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
        ];

        if ($request->status == 'approved' && $kyc->status != 'approved') {
            $data['verified_at'] = now();
            $data['verified_by'] = Auth::guard('admin')->id();
        }

        $kyc->update($data);

        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC status updated successfully.');
    }
}