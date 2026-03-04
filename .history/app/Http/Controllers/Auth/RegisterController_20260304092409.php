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
        $productsJs = [];
        $countries = Country::where('is_active', 1)->orderBy('name')->get();

        return view('auth.register', compact('packages', 'productsJs', 'countries'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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
            return back()->withErrors(['placement_position' => 'This position is already taken.'])->withInput();
        }

        DB::beginTransaction();

        try {

            $user = $this->create(
                $request->all(),
                $sponsorUser->id,
                $placementUser->id
            );

            // ❌ NO PV
            // ❌ NO BONUSES
            // ❌ NO COMMISSIONS

            $verificationCode = rand(100000, 999999);
            $user->verification_code = (string)$verificationCode;
            $user->save();

            Mail::to($user->email)
                ->send(new VerificationCodeMail($verificationCode, $user->name));

            Session::put('pending_user_id', $user->id);
            Session::put('package_id', $request->package_id);
            Session::put('product_id', $request->product_id);

            DB::commit();

            return redirect()->route('verification.notice');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());

            return back()->withErrors([
                'general' => 'Registration failed. Please try again.'
            ])->withInput();
        }
    }

    protected function create(array $data, $sponsorId, $placementId)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'username' => $this->generateUsername($data['name']),
            'referral_code' => $this->generateReferralCode(),
            'sponsor_id' => $sponsorId,
            'placement_id' => $placementId,
            'placement_position' => $data['placement_position'],
            'package_id' => $data['package_id'],
            'country' => $data['country'],
            'state' => $data['state'],
            'pickup_center_id' => $data['pickup_center_id'],
            'product_id' => $data['product_id'],
            'status' => 'pending_verification',
            'registration_date' => now(),

            // All PV zero at registration
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
    }

    private function generateUsername($name)
    {
        $base = Str::slug($name, '');
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