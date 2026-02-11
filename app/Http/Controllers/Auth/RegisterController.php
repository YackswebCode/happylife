<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Product;
use App\Models\User;
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
        // Show all packages; product list prepared for JS
        $packages = Package::all();

        $productsJs = [];
        foreach ($packages as $pkg) {
            $products = Product::where('package_id', $pkg->id)->get();
            $productsJs[$pkg->id] = $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'package_id' => $p->package_id
            ])->toArray();
        }

        return view('auth.register', compact('packages', 'productsJs'));
    }

    public function register(Request $request)
    {
        // VALIDATE
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'sponsor_code' => 'required|exists:users,referral_code',
            'placement_code' => 'required|exists:users,referral_code',
            'placement_position' => 'required|in:left,right',
            'package_id' => 'required|exists:packages,id',
            'country' => 'required|string',
            'state' => 'required|string',
            'pickup_center' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'terms' => 'accepted'
        ]);

        // Find sponsor and placement users by referral code
        $sponsorUser = User::where('referral_code', $request->sponsor_code)->first();
        $placementUser = User::where('referral_code', $request->placement_code)->first();

        if (!$sponsorUser) {
            return back()->withErrors(['sponsor_code' => 'Invalid sponsor referral code.'])->withInput();
        }

        if (!$placementUser) {
            return back()->withErrors(['placement_code' => 'Invalid placement referral code.'])->withInput();
        }

        // Check if placement position is available
        $existingPlacement = User::where('placement_id', $placementUser->id)
            ->where('placement_position', $request->placement_position)
            ->first();

        if ($existingPlacement) {
            return back()->withErrors(['placement_position' => 'This position is already taken. Please choose the other side.'])->withInput();
        }

        // Load package (needed to determine direct bonus and PV)
        $package = Package::find($request->package_id);

        if (!$package) {
            return back()->withErrors(['package_id' => 'Selected package not found.'])->withInput();
        }

        // Everything that follows should happen in a DB transaction
        DB::beginTransaction();

        try {
            // CREATE USER
            $user = $this->create($request->all(), $sponsorUser->id, $placementUser->id);

            // Generate verification code and save
            $verificationCode = rand(100000, 999999);
            $user->verification_code = (string)$verificationCode;
            $user->save();

            // Send verification email (best-effort)
            try {
                Mail::to($user->email)->send(new VerificationCodeMail($verificationCode, $user->name));
            } catch (Exception $e) {
                // log but don't fail registration
                Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            // Store user ID in session for verification step
            Session::put('pending_user_id', $user->id);
            Session::put('package_id', $request->package_id);
            Session::put('product_id', $request->product_id);

            DB::commit();

            return redirect()->route('verification.notice');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Registration failed. Please try again later.'])->withInput();
        }
    }

    /**
     * Create user record
     */
    protected function create(array $data, $sponsorId, $placementId)
    {
        $username = $this->generateUsername($data['name']);
        $referralCode = $this->generateReferralCode();

        // Use null defaults for optional fields if not provided
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'username' => $username,
            'referral_code' => $referralCode,
            'sponsor_id' => $sponsorId,
            'placement_id' => $placementId,
            'placement_position' => $data['placement_position'],
            'package_id' => $data['package_id'],
            'country' => $data['country'],
            'state' => $data['state'],
            'pickup_center' => $data['pickup_center'],
            'product_id' => $data['product_id'],
            'status' => 'pending_verification',
            'registration_date' => now(),
            'verification_code' => null,
            'role' => 'member',
            'commission_wallet_balance' => 0,
            'registration_wallet_balance' => 0,
            'rank_wallet_balance' => 0,
            'shopping_wallet_balance' => 0,
            // Initialize count and PV fields to 0
            'left_count' => 0,
            'right_count' => 0,
            'left_pv' => 0,
            'right_pv' => 0,
            'total_pv' => 0,
            'current_pv' => 0,
            'direct_bonus_total' => 0,
            'direct_sponsors_count' => 0,
            'indirect_bonus_total' => 0,
            'indirect_level_2_bonus_total' => 0,
            'indirect_level_3_bonus_total' => 0,
        ]);
    }

    private function generateUsername($name)
    {
        $baseUsername = Str::slug($name, '');
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    private function generateReferralCode()
    {
        $code = 'HL' . strtoupper(Str::random(6));

        while (User::where('referral_code', $code)->exists()) {
            $code = 'HL' . strtoupper(Str::random(6));
        }

        return $code;
    }

    public function checkUsername($username)
    {
        $exists = User::where('username', $username)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkReferralCode($code)
    {
        $user = User::where('referral_code', $code)->first();
        if ($user) {
            return response()->json([
                'exists' => true,
                'name' => $user->name,
                'username' => $user->username
            ]);
        }
        return response()->json(['exists' => false]);
    }
}