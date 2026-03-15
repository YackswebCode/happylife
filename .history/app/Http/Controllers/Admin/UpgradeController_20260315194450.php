<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upgrade;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $upgrades = Upgrade::with(['user', 'oldPackage', 'newPackage'])
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
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.upgrades.index', compact('upgrades'));
    }

    public function show(Upgrade $upgrade)
    {
        $upgrade->load(['user', 'oldPackage', 'newPackage']);
        return view('admin.upgrades.show', compact('upgrade'));
    }
}