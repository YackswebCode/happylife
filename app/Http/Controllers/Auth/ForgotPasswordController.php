<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form to request a password reset code.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a 6-digit verification code to the user's email.
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate 6-digit numeric code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store code and update timestamp
        $user->verification_code = $code;
        $user->save();

        // Send email
        Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user->name));

        // Store email in session for the next steps
        Session::put('password_reset_email', $user->email);

        return redirect()->route('password.verify.code')
                         ->with('status', 'We have sent a 6-digit verification code to your email.');
    }

    /**
     * Resend a new 6-digit code to the same email.
     */
    public function resendResetCode(Request $request)
    {
        $email = Session::get('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Session expired. Please request again.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'User not found.']);
        }

        // Generate new code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->verification_code = $code;
        $user->save();

        // Resend email
        Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user->name));

        return redirect()->route('password.verify.code')
                         ->with('success', 'A new verification code has been sent to your email.');
    }

    /**
     * Show the form to verify the 6-digit code.
     */
    public function showVerifyCodeForm()
    {
        if (!Session::has('password_reset_email')) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Please request a password reset first.']);
        }

        $email = Session::get('password_reset_email');
        return view('auth.verify-reset-code', compact('email'));
    }

    /**
     * Verify the submitted 6-digit code.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $email = Session::get('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Session expired. Please request again.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user || $user->verification_code !== $request->code) {
            return back()->withErrors(['code' => 'The verification code is incorrect.']);
        }

        // Check if code is still valid (30 minutes expiry based on updated_at)
        $expiryTime = Carbon::parse($user->updated_at)->addMinutes(30);
        if (Carbon::now()->gt($expiryTime)) {
            return back()->withErrors(['code' => 'This code has expired. Please request a new one.']);
        }

        // Code is valid – mark session as verified
        Session::put('password_reset_verified', true);

        return redirect()->route('password.reset.form');
    }

    /**
     * Show the form to reset the password.
     */
    public function showResetForm()
    {
        if (!Session::has('password_reset_email') || !Session::get('password_reset_verified')) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Unauthorized access. Please restart the process.']);
        }

        return view('auth.reset-password');
    }

    /**
     * Update the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = Session::get('password_reset_email');
        if (!$email || !Session::get('password_reset_verified')) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Session invalid. Please request again.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('login')
                             ->withErrors(['email' => 'User not found.']);
        }

        // Update password and clear verification code
        $user->password = Hash::make($request->password);
        $user->verification_code = null;
        $user->save();

        // Clear session
        Session::forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('login')
                         ->with('status', 'Your password has been reset successfully! You can now log in.');
    }
}