<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
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
        
        // Redirect based on user status
        switch ($user->status) {
            case 'pending_verification':
                if (!$user->email_verified_at) {
                    return redirect()->route('verification.notice');
                }
                break;
                
            case 'pending_payment':
                if ($user->email_verified_at) {
                    return redirect()->route('payment.show');
                }
                break;
                
            case 'active':
                // User is active, allow access
                return $next($request);
                
            default:
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is not active. Please contact support.'
                ]);
        }

        return $next($request);
    }
}