<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedSession
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
        
        // Check if user has verified email
        if (!$user->email_verified_at) {
            return redirect()->route('verification.notice');
        }

        // Check if user is already active (already paid)
        if ($user->status === 'active') {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}