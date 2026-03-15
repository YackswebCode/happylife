<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate request – now using 'email'
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt login using email
        if (Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password']
        ], $request->boolean('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            /**
             * 1️⃣ Check Email Verification
             */
            if (!$user->email_verified_at) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Please verify your email address first.',
                ])->onlyInput('email');
            }

            /**
             * 2️⃣ Check Payment Status
             */
            if ($user->payment_status !== 'paid') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Please complete your payment to activate your account.',
                ])->onlyInput('email');
            }

            /**
             * 3️⃣ Check Account Status
             */
            if ($user->status !== 'active') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact support.',
                ])->onlyInput('email');
            }

            // All checks passed
            return redirect()->intended('dashboard');
        }

        /**
         * Login Failed
         */
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}