<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Services\MlmDistributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $mlmService;

    public function __construct(MlmDistributionService $mlmService)
    {
        $this->mlmService = $mlmService;
    }

    /**
     * Show the payment page with package details.
     */
    public function showPayment()
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');
        if (!$user->email_verified_at) return redirect()->route('verification.notice');
        if ($user->status === 'active') return redirect()->route('dashboard');

        $package = Package::find($user->package_id);

        if (!$package) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Package not found.']);
        }

        return view('auth.payment', compact('package', 'user'));
    }

    /**
     * Activate user after successful online payment.
     * Called via AJAX from the payment view.
     */
    public function activateUser(Request $request)
    {
        $request->validate([
            'reference' => 'required',
            'gateway' => 'required|in:paystack,flutterwave',
            'package_id' => 'required|exists:packages,id',
            'amount' => 'required|numeric'
        ]);

        $user = Auth::user();
        if (!$user || $user->status === 'active') {
            return response()->json(['success' => false], 400);
        }

        $package = Package::find($request->package_id);
        if (!$package || $package->price != $request->amount) {
            return response()->json(['success' => false], 400);
        }

        DB::beginTransaction();

        try {
            // Record payment as paid
            $payment = Payment::create([
                'user_id'           => $user->id,
                'package_id'        => $package->id,
                'amount'            => $package->price,
                'payment_method'    => $request->gateway,
                'reference'         => strtoupper(substr($request->gateway, 0, 2)) . time(),
                'gateway_reference' => $request->reference,
                'status'            => 'paid',
            ]);

            // Activate user
            $user->status = 'active';
            $user->payment_status = 'paid';
            $user->activated_at = now();
            $user->package_id = $package->id;
            $user->save();

            // Run MLM distribution (PV, bonuses, pairing)
            $this->mlmService->distribute($payment);

            DB::commit();

            // Logout after activation – user must login again
            Auth::logout();

            return response()->json([
                'success'  => true,
                'redirect' => route('login')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Online payment activation failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage()
            ]);
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Handle bank transfer proof upload.
     * Payment will be pending until admin approval.
     */
    public function processBankTransfer(Request $request)
    {
        $request->validate([
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'User not authenticated.');
        }

        if ($user->status === 'active') {
            return back()->with('error', 'You are already activated.');
        }

        $package = Package::find($user->package_id);

        if (!$package) {
            return back()->with('error', 'Package not found.');
        }

        // Upload proof file
        $filePath = $request->file('proof_of_payment')->store('payments', 'public');

        // Save pending payment
        Payment::create([
            'user_id'           => $user->id,
            'package_id'        => $package->id,
            'amount'            => $package->price,
            'payment_method'    => 'bank_transfer',
            'reference'         => 'BT' . time(),
            'gateway_reference' => null,
            'status'            => 'pending',
            'proof_url'         => $filePath,
            'authorization_url' => null,
            'gateway_response'  => null,
            'description'       => 'Bank transfer awaiting admin approval',
        ]);

        return back()->with('success', 'Payment proof submitted successfully. Await admin approval.');
    }
}