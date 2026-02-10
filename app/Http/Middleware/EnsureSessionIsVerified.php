<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSessionIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        // If user is logged in and email_verified_at is set -> ok
        if (Auth::check() && !is_null(Auth::user()->email_verified_at)) {
            return $next($request);
        }

        // Or if session contains verified registration id:
        if ($request->session()->has('registration_verified_user_id')) {
            return $next($request);
        }

        // else redirect back to verification page
        return redirect()->route('verification.notice')->with('error', 'Please verify your email before continuing.');
    }
}
