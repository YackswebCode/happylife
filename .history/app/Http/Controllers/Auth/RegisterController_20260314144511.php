<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $packages = Package::where('is_active', 1)->orderBy('order')->get();
        $countries = Country::where('is_active', 1)->orderBy('name')->get();
        $productsJs = [];

        return view('auth.register', compact('packages', 'countries', 'productsJs'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',   
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'sponsor_code' => 'required|exists:users,referral_code',
            'placement_code' => 'required|exists:users,referral_code',
            'placement_position' => 'required|in:left,right',
            'package_id' => 'required|exists:packages,id',
            'country' => 'required|string',
            'state' => 'required|string',
            'pickup_center_id' => 'required|integer|exists:pickup_centers,id',
            'product_id' => 'required|integer',
            'terms' => 'accepted'
        ]);

        $sponsorUser = User::where('referral_code', $request->sponsor_code)->first();
        $placementUser = User::where('referral_code', $request->placement_code)->first();

        if (!$sponsorUser || !$placementUser) {
            return back()->withErrors(['general' => 'Invalid sponsor or placement code.'])->withInput();
        }

        $existingPlacement = User::where('placement_id', $placementUser->id)
            ->where('placement_position', $request->placement_position)
            ->first();

        if ($existingPlacement) {
            return back()->withErrors(['placement_position' => 'Position already taken.'])->withInput();
        }

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $request->name,
                'gender'=> $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'username' => $this->generateUsername($request->name),
                'referral_code' => $this->generateReferralCode(),
                'sponsor_id' => $sponsorUser->id,
                'placement_id' => $placementUser->id,
                'placement_position' => $request->placement_position,
                'package_id' => $request->package_id,
                'country' => $request->country,
                'state' => $request->state,
                'pickup_center_id' => $request->pickup_center_id,
                'product_id' => $request->product_id,
                'status' => 'pending_verification',
                'payment_status' => 'unpaid',
                'registration_date' => now(),

                // All values start at 0
                'left_count' => 0,
                'right_count' => 0,
                'left_pv' => 0,
                'right_pv' => 0,
                'total_pv' => 0,
                'current_pv' => 0,
                'commission_wallet_balance' => 0,
                'direct_bonus_total' => 0,
                'direct_sponsors_count' => 0,
            ]);

            $verificationCode = rand(100000, 999999);
            $user->verification_code = $verificationCode;
            $user->save();

            Mail::to($user->email)
                ->send(new VerificationCodeMail(
                    $verificationCode,
                    $user->name,
                    $user->username
                ));

            Session::put('pending_user_id', $user->id);

            DB::commit();

            return redirect()->route('verification.notice');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());

            return back()->withErrors(['general' => 'Registration failed.'])->withInput();
        }
    }

  private function generateUsername($name)
    {
        $base = 'happylif_' . Str::slug($name, '');
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $counter++;
        }

        return $username;
    }

    private function generateReferralCode()
    {
        do {
            $code = 'HL' . strtoupper(Str::random(6));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}