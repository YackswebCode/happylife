<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // First check: Email verification
            if (!$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address first.',
                ])->onlyInput('email');
            }
            
            // Second check: Payment status
            if ($user->payment_status !== 'paid') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please complete your payment to activate your account.',
                ])->onlyInput('email');
            }
            
            // Third check: Account status
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact support.',
                ])->onlyInput('email');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}