<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return redirect()->route('verification.notice');
        }
        
        // Check if user has paid
        if ($user->payment_status !== 'paid') {
            return redirect()->route('payment.show');
        }
        
        // User is verified and paid, show dashboard
        return view('dashboard');
    }
}