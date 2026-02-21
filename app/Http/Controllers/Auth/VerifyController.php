<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class VerifyController extends Controller
{
    // Show verification form
    public function showVerifyForm()
    {
        if (!Session::has('pending_user_id')) {
            return redirect()->route('register');
        }
        
        $userId = Session::get('pending_user_id');
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'User not found.']);
        }
        
        return view('auth.verify', compact('user'));
    }

    // Verify submitted code
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $userId = Session::get('pending_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'User not found.']);
        }

        if ($user->verification_code != $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.'])->withInput();
        }

        // Clear code & mark email verified
        $user->verification_code = null;
        $user->email_verified_at = now();
        $user->save();

        // Log in user
        Auth::login($user);

        // Clear session
        Session::forget('pending_user_id');

        return redirect()->route('payment.show')
            ->with('success', 'Email verified successfully! Please complete your payment.');
    }

    // Resend verification code
    public function resend(Request $request)
    {
        $userId = Session::get('pending_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'User not found.']);
        }

        // Generate new 6-digit code
        $verificationCode = rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->save();

        // Send email with inline HTML
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode, $user->name));
            return back()->with('success', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Verification email failed: '.$e->getMessage());
            return back()->withErrors(['error' => 'Failed to send verification code. Please try again.']);
        }
    }
}
