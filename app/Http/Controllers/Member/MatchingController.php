<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class MatchingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total matching bonus earned (from users table)
        $totalMatchingBonus = $user->matching_pv_bonus_total ?? 0;

        // Recent matching bonus transactions (if any)
        $recentTransactions = WalletTransaction::where('user_id', $user->id)
            ->where('description', 'like', '%matching%')
            ->orWhere('description', 'like', '%pairing%')
            ->latest()
            ->take(10)
            ->get();

        return view('member.matching.index', compact(
            'user',
            'totalMatchingBonus',
            'recentTransactions'
        ));
    }
}