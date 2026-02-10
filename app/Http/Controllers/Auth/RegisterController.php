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

            // Award registration-related bonuses (direct sponsor + indirect)
            $this->awardRegistrationBonuses($user, $package, $sponsorUser);

            // Propagate PV and compute pair bonuses up the placement tree
            $pv = (float) ($package->pv ?? 0.0);
            if ($pv > 0) {
                $this->propagatePvAndComputePairs($placementUser, $request->placement_position, $pv);
            }

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
        ]);
    }

    /**
     * Award registration bonuses:
     * - direct sponsor bonus (fixed by package)
     * - indirect sponsor bonus: 10% of direct bonus up to 2 levels above sponsor
     */
    private function awardRegistrationBonuses(User $newUser, Package $package, ?User $directSponsor)
    {
        // Determine direct sponsor bonus (in Naira)
        $directBonus = $this->getDirectSponsorBonus($package);

        if ($directBonus > 0 && $directSponsor) {
            // Credit direct sponsor's commission wallet
            $directSponsor->commission_wallet_balance = $this->safeAddAmount($directSponsor->commission_wallet_balance, $directBonus);
            $directSponsor->save();

            // Optionally: create commission/wallet transaction record here.

            // Indirect bonuses: climb up the sponsor chain up to 2 levels and award 10% of direct bonus to each
            $generation = 1;
            $currentSponsor = $directSponsor;
            while ($generation <= 2 && $currentSponsor && $currentSponsor->sponsor_id) {
                $upline = User::find($currentSponsor->sponsor_id);
                if (!$upline) {
                    break;
                }

                $indirectAmount = round($directBonus * 0.10, 2); // 10% of direct bonus
                if ($indirectAmount > 0) {
                    $upline->commission_wallet_balance = $this->safeAddAmount($upline->commission_wallet_balance, $indirectAmount);
                    $upline->save();

                    // Optionally: create commission/wallet transaction record for indirect bonus
                }

                // Move one level up
                $currentSponsor = $upline;
                $generation++;
            }
        }
    }

    /**
     * Walk up the placement tree, add PV to left/right PV counters and compute pair bonuses.
     *
     * Rules implemented:
     * - When a new user registers with PV, we add that PV to each ancestor on the side (left/right).
     * - For each ancestor we compute how many pairs can be formed: floor(left_pv/40) and floor(right_pv/40),
     *   pairs = min(floor(left_pv/40), floor(right_pv/40))
     * - For each pair, ancestor receives ₦1,500 per pair and the used PV is deducted from both sides.
     *
     * Note: This mutates ancestor->left_pv / right_pv and ancestor->commission_wallet_balance.
     */
    private function propagatePvAndComputePairs(User $placementUser, string $side, float $pv)
    {
        $ancestor = $placementUser;

        while ($ancestor) {
            // Ensure numeric values
            $ancestor->left_pv = isset($ancestor->left_pv) ? (float)$ancestor->left_pv : 0.0;
            $ancestor->right_pv = isset($ancestor->right_pv) ? (float)$ancestor->right_pv : 0.0;
            $ancestor->commission_wallet_balance = isset($ancestor->commission_wallet_balance) ? (float)$ancestor->commission_wallet_balance : 0.0;

            // Add PV to the correct side
            if ($side === 'left') {
                $ancestor->left_pv = $this->safeAddAmount($ancestor->left_pv, $pv);
            } else {
                $ancestor->right_pv = $this->safeAddAmount($ancestor->right_pv, $pv);
            }

            // Compute pairs available
            $leftPairs = floor($ancestor->left_pv / 40);
            $rightPairs = floor($ancestor->right_pv / 40);
            $pairs = (int) min($leftPairs, $rightPairs);

            if ($pairs > 0) {
                // Pair bonus amount per pair
                $pairAmountPerPair = 1500.00;
                $pay = $pairs * $pairAmountPerPair;

                // Credit commission wallet
                $ancestor->commission_wallet_balance = $this->safeAddAmount($ancestor->commission_wallet_balance, $pay);

                // Deduct used PV from both sides
                $ancestor->left_pv = $ancestor->left_pv - ($pairs * 40);
                $ancestor->right_pv = $ancestor->right_pv - ($pairs * 40);

                // Optionally: create wallet transaction records
            }

            $ancestor->save();

            // Move to placement parent (the user above them)
            if ($ancestor->placement_id) {
                $ancestor = User::find($ancestor->placement_id);
            } else {
                $ancestor = null;
            }
        }
    }

    /**
     * Map package -> fixed direct sponsor bonus (₦).
     * Update mapping to match your packages table or replace this with a DB column.
     */
    private function getDirectSponsorBonus(Package $package): float
    {
        // Prefer reading from DB if available
        if (isset($package->direct_bonus) && $package->direct_bonus !== null) {
            return (float) $package->direct_bonus;
        }

        // Fallback mapping from your documentation (update if package names differ)
        $name = strtolower(trim($package->name ?? $package->title ?? ''));

        $mapping = [
            'sapphire' => 1000.00,
            'ohekem' => 1500.00,
            'lifestyle' => 10250.00,
            'business guru' => 56250.00,
        ];

        return $mapping[$name] ?? 0.00;
    }

    /**
     * Safe decimal addition to avoid nulls or string values.
     */
    private function safeAddAmount($current, $add)
    {
        $current = is_null($current) ? 0.0 : (float)$current;
        $add = (float)$add;
        return round($current + $add, 2);
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
