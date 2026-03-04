<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\Country;
use App\Models\PickupCenter;
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

        // If you have a separate products table, you can still load them here.
        // Otherwise, leave as empty array.
        $productsJs = [];

        $countries = Country::where('is_active', 1)
            ->orderBy('name')
            ->get();

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
            // ✅ Product ID is required but no foreign key check
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

        $package = Package::find($request->package_id);
        if (!$package) {
            return back()->withErrors(['package_id' => 'Invalid package selected.'])->withInput();
        }

        DB::beginTransaction();

        try {
            $user = $this->create(
                $request->all(),
                $sponsorUser->id,
                $placementUser->id
            );

            // Process MLM Bonuses
            $this->processBonuses($user, $package);

            // Verification Code
            $verificationCode = rand(100000, 999999);
            $user->verification_code = (string)$verificationCode;
            $user->save();

            try {
                Mail::to($user->email)
                    ->send(new VerificationCodeMail($verificationCode, $user->name));
            } catch (Exception $e) {
                Log::error('Mail failed: ' . $e->getMessage());
            }

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
        $username = $this->generateUsername($data['name']);
        $referralCode = $this->generateReferralCode();

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'username' => $username,
            'referral_code' => $referralCode,
            'sponsor_id' => $sponsorId,
            'placement_id' => $placementId,
            'placement_position' => $data['placement_position'],
            'package_id' => $data['package_id'],
            'country' => $data['country'],
            'state' => $data['state'],
            'pickup_center_id' => $data['pickup_center_id'],
            'product_id' => $data['product_id'], // stores the selected product/package ID
            'status' => 'pending_verification',
            'registration_date' => now(),
            'role' => 'member',

            // Wallets
            'commission_wallet_balance' => 0,
            'registration_wallet_balance' => 0,
            'rank_wallet_balance' => 0,
            'shopping_wallet_balance' => 0,

            // Binary Data
            'left_count' => 0,
            'right_count' => 0,
            'left_pv' => 0,
            'right_pv' => 0,
            'total_pv' => 0,
            'current_pv' => 0,

            // Bonus Tracking
            'direct_bonus_total' => 0,
            'indirect_level_2_bonus_total' => 0,
            'indirect_level_3_bonus_total' => 0,
            'direct_sponsors_count' => 0,
        ]);
    }

    protected function processBonuses(User $newUser, Package $package)
    {
        $directBonus = $package->direct_bonus_amount;

        $level1 = User::find($newUser->sponsor_id);
        if ($level1) {
            $level1->increment('direct_bonus_total', $directBonus);
            $level1->increment('commission_wallet_balance', $directBonus);
            $level1->increment('direct_sponsors_count');
        }

        if ($level1 && $level1->sponsor_id) {
            $level2 = User::find($level1->sponsor_id);
            if ($level2) {
                $level2Bonus = $directBonus * 0.05;
                $level2->increment('indirect_level_2_bonus_total', $level2Bonus);
                $level2->increment('commission_wallet_balance', $level2Bonus);
            }
        }

        if (isset($level2) && $level2 && $level2->sponsor_id) {
            $level3 = User::find($level2->sponsor_id);
            if ($level3) {
                $level3Bonus = $directBonus * 0.03;
                $level3->increment('indirect_level_3_bonus_total', $level3Bonus);
                $level3->increment('commission_wallet_balance', $level3Bonus);
            }
        }
    }

    private function generateUsername($name)
    {
        $base = Str::slug($name, '');
        $username = $base;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
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