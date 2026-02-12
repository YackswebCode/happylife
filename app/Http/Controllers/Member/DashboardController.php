<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Commission;
use App\Models\WalletTransaction;
use App\Models\Rank;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user is logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has verified email
        if (!$user->email_verified_at) {
            Auth::logout();
            return redirect()->route('verification.notice')->withErrors([
                'email' => 'Please verify your email address.'
            ]);
        }

        // Check if user has paid
        if ($user->payment_status !== 'paid') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Please complete your payment to activate your account.'
            ]);
        }

        // Check if user is active
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is not active.'
            ]);
        }

        // Load relationships
        $user->load(['package', 'rank']);

        /*
        |--------------------------------------------------------------------------
        | NEXT RANK LOGIC
        |--------------------------------------------------------------------------
        */
        $nextRank = null;

        if ($user->rank) {
            $nextRank = Rank::where('level', '>', $user->rank->level)
                ->where('is_active', true)
                ->orderBy('level')
                ->first();
        } else {
            $nextRank = Rank::where('is_active', true)
                ->orderBy('level')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | FETCH WALLET BALANCES FROM USERS TABLE
        |--------------------------------------------------------------------------
        */
        $commissionBalance   = $user->commission_wallet_balance ?? 0;
        $registrationBalance = $user->registration_wallet_balance ?? 0;
        $rankBalance         = $user->rank_wallet_balance ?? 0;
        $shoppingBalance     = $user->shopping_wallet_balance ?? 0;

        /*
        |--------------------------------------------------------------------------
        | USER STATISTICS
        |--------------------------------------------------------------------------
        */
        $stats = [
            'total_pv'                  => $user->total_pv ?? 0,
            'current_pv'                => $user->current_pv ?? 0,

            'commission_wallet_balance' => $commissionBalance,
            'registration_balance'      => $registrationBalance,
            'rank_balance'              => $rankBalance,
            'shopping_balance'          => $shoppingBalance,

            'left_count'                => $user->left_count ?? 0,
            'right_count'               => $user->right_count ?? 0,
            'direct_downlines'          => $user->directDownlines()->count(),
            'total_downlines'           => $this->getTotalDownlines($user),
        ];

        // Get recent activities
        $recent_activities = $this->getRecentActivities($user);

        return view('member.dashboard', compact(
            'user',
            'stats',
            'recent_activities',
            'nextRank'
        ));
    }

    /**
     * Recursively count all downlines
     */
    private function getTotalDownlines($user)
    {
        $count = $user->directDownlines()->count();

        foreach ($user->directDownlines as $downline) {
            $count += $this->getTotalDownlines($downline);
        }

        return $count;
    }

    /**
     * Get recent commissions & wallet transactions
     */
    private function getRecentActivities($user)
    {
        $activities = collect();

        // Recent commissions
        try {
            $commissions = Commission::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($commission) {
                    return [
                        'type'        => 'commission',
                        'title'       => 'Commission Earned',
                        'description' => $commission->description,
                        'amount'      => $commission->amount,
                        'date'        => $commission->created_at,
                        'icon'        => 'bi-cash-stack',
                        'color'       => 'text-green-600'
                    ];
                });

            $activities = $activities->merge($commissions);
        } catch (\Exception $e) {
            // Table may not exist yet
        }

        // Recent wallet transactions
        try {
            $wallet_transactions = WalletTransaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($transaction) {
                    return [
                        'type'        => 'wallet',
                        'title'       => ucfirst($transaction->type) . ' Transaction',
                        'description' => $transaction->description,
                        'amount'      => $transaction->amount,
                        'date'        => $transaction->created_at,
                        'icon'        => $transaction->type == 'credit' ? 'bi-plus-circle' : 'bi-dash-circle',
                        'color'       => $transaction->type == 'credit' ? 'text-green-600' : 'text-red-600'
                    ];
                });

            $activities = $activities->merge($wallet_transactions);
        } catch (\Exception $e) {
            // Table may not exist yet
        }

        return $activities->sortByDesc(function ($activity) {
            return $activity['date'];
        })->take(10);
    }
}
