<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ----- GUARDS -----
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('verification.notice');
        }

        if ($user->payment_status !== 'paid') {
            return redirect()->route('payment.show');
        }

        // ----- EAGER LOAD RELATIONSHIPS -----
        $user->load([
            'package',                // active package
            'rank',                  // current rank
            'wallets',              // all wallet entries
        ]);

        // ----- LEFT / RIGHT NETWORK COUNTS -----
        // Assuming you have a 'referrals' relationship and a 'position' column (left/right)
        // Adjust the relationship names to match your actual database design
        $leftCount = $user->referrals()->where('position', 'left')->count();
        $rightCount = $user->referrals()->where('position', 'right')->count();

        // ----- PV CALCULATIONS -----
        // If $user->left_pv and $user->right_pv are columns, use them directly
        $totalPv = $user->left_pv + $user->right_pv;
        // If you have a 'current_pv' column for monthly/cycle PV, use it; otherwise fallback to total
        $currentPv = $user->current_pv ?? $totalPv;

        // ----- WALLET BALANCES -----
        // Transform wallets collection into a key-value array (type => balance)
        $walletBalances = $user->wallets->pluck('balance', 'type');

        // ----- BONUS TOTALS -----
        // These may be columns on the 'users' table or accessors.
        // If they are stored in a separate 'bonus_totals' table, you need to fetch them.
        // The code below assumes they are attributes of the User model (directly or via accessor).
        // If not, you'll need to sum from transactions – adjust accordingly.

        // ----- NEXT RANK -----
        $nextRank = Rank::where('required_pv', '>', $totalPv)
                        ->orderBy('required_pv', 'asc')
                        ->first();

        // ----- BUILD STATS ARRAY -----
        $stats = [
            // PV
            'total_pv'              => $totalPv,
            'current_pv'           => $currentPv,

            // Network counts
            'left_count'           => $leftCount,
            'right_count'          => $rightCount,

            // Wallet balances (with fallback to 0)
            'commission_balance'   => $walletBalances['commission'] ?? 0,
            'registration_balance' => $walletBalances['registration'] ?? 0,
            'rank_balance'         => $walletBalances['rank'] ?? 0,
            'shopping_balance'     => $walletBalances['shopping'] ?? 0,
        ];

        // ----- PASS EVERYTHING TO VIEW -----
        return view('member.dashboard', [
            'user'     => $user,
            'stats'    => $stats,
            'nextRank' => $nextRank,
        ]);
    }
}