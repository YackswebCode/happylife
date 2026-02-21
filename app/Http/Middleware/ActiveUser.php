<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user is active
        if ($user->status !== 'active') {
            if ($user->email_verified_at && $user->status === 'pending_verification') {
                return redirect()->route('payment.show');
            }
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is not active. Please complete the registration process.'
            ]);
        }

        return $next($request);
    }
}