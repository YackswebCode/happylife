<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Commission;
use App\Models\WalletTransaction;

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
        
        // Get user statistics - FIXED: Use correct relationship names
        $stats = [
            'total_pv' => $user->total_pv,
            'current_pv' => $user->current_pv,
            'commission_balance' => $user->commission_wallet_balance ?? 0,
            'registration_balance' => $user->registration_wallet_balance ?? 0,
            'rank_balance' => $user->rank_wallet_balance ?? 0,
            'shopping_balance' => $user->shopping_wallet_balance ?? 0,
            'left_count' => $user->left_count,
            'right_count' => $user->right_count,
            'direct_downlines' => $user->directDownlines()->count(), // Fixed relationship
            'total_downlines' => $this->getTotalDownlines($user),
        ];
        
        // Get recent activities
        $recent_activities = $this->getRecentActivities($user);
        
        return view('member.dashboard', compact('user', 'stats', 'recent_activities'));
    }
    
    private function getTotalDownlines($user)
    {
        // Use the relationship properly
        $count = $user->directDownlines()->count();
        
        // Recursively count downlines of downlines
        foreach ($user->directDownlines as $downline) {
            $count += $this->getTotalDownlines($downline);
        }
        
        return $count;
    }
    
    private function getRecentActivities($user)
    {
        $activities = collect();
        
        // Get recent commissions
        try {
            $commissions = Commission::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($commission) {
                    return [
                        'type' => 'commission',
                        'title' => 'Commission Earned',
                        'description' => $commission->description,
                        'amount' => $commission->amount,
                        'date' => $commission->created_at,
                        'icon' => 'bi-cash-stack',
                        'color' => 'text-green-600'
                    ];
                });
            
            $activities = $activities->merge($commissions);
        } catch (\Exception $e) {
            // Commission table might not exist yet
        }
        
        // Get recent wallet transactions
        try {
            $wallet_transactions = WalletTransaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($transaction) {
                    return [
                        'type' => 'wallet',
                        'title' => ucfirst($transaction->type) . ' Transaction',
                        'description' => $transaction->description,
                        'amount' => $transaction->amount,
                        'date' => $transaction->created_at,
                        'icon' => $transaction->type == 'credit' ? 'bi-plus-circle' : 'bi-dash-circle',
                        'color' => $transaction->type == 'credit' ? 'text-green-600' : 'text-red-600'
                    ];
                });
            
            $activities = $activities->merge($wallet_transactions);
        } catch (\Exception $e) {
            // WalletTransaction table might not exist yet
        }
        
        // Sort by date and take only 10
        return $activities->sortByDesc(function($activity) {
            return $activity['date'];
        })->take(10);
    }
}