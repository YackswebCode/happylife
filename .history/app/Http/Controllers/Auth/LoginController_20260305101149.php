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
        // Validate request
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Attempt login using username
        if (Auth::attempt([
            'username' => $credentials['username'],
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
                    'username' => 'Please verify your email address first.',
                ])->onlyInput('username');
            }

            /**
             * 2️⃣ Check Payment Status
             */
            if ($user->payment_status !== 'paid') {
                Auth::logout();

                return back()->withErrors([
                    'username' => 'Please complete your payment to activate your account.',
                ])->onlyInput('username');
            }

            /**
             * 3️⃣ Check Account Status
             */
            if ($user->status !== 'active') {
                Auth::logout();

                return back()->withErrors([
                    'username' => 'Your account is not active. Please contact support.',
                ])->onlyInput('username');
            }

            // All checks passed
            return redirect()->intended('dashboard');
        }

        /**
         * Login Failed
         */
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
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