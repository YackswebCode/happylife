<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $commissions = Commission::with(['user', 'fromUser'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                })->orWhereHas('fromUser', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('description', 'like', "%{$search}%");
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
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

        return view('admin.commissions.index', compact('commissions'));
    }

    // Optional show
    public function show(Commission $commission)
    {
        $commission->load(['user', 'fromUser', 'fromPackage']);
        return view('admin.commissions.show', compact('commission'));
    }
}