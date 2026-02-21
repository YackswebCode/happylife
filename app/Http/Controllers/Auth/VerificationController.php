<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VerificationController extends Controller
{
    public function showVerificationNotice()
    {
        if (!Session::has('pending_user_id')) {
            return redirect()->route('register');
        }
        
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|digits:6'
        ]);

        $userId = Session::get('pending_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'User not found.']);
        }

        if ($user->verification_code != $request->verification_code) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.']);
        }

        // Clear verification code and update status
        $user->verification_code = null;
        $user->email_verified_at = now();
        $user->save();

        // Clear session
        Session::forget('pending_user_id');

        // Redirect to payment page
        return redirect()->route('payment.show')
            ->with('success', 'Email verified successfully! Please complete your payment.');
    }

    public function resendCode()
    {
        $userId = Session::get('pending_user_id');
        $user = User::find($userId);

        if ($user) {
            $verificationCode = rand(100000, 999999);
            $user->verification_code = $verificationCode;
            $user->save();

            // Resend email
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

            return back()->with('success', 'New verification code sent to your email.');
        }

        return back()->withErrors(['error' => 'User not found.']);
    }
}