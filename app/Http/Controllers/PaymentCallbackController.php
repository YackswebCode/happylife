<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\MlmService;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    protected $mlmService;

    public function __construct(MlmService $mlmService)
    {
        $this->mlmService = $mlmService;
    }

    /**
     * Handle successful online payment redirect.
     * GET /payment/callback?reference=xxx&gateway=paystack
     */
    public function handle(Request $request)
    {
        $reference = $request->get('reference');
        $gateway = $request->get('gateway');

        $payment = Payment::where('gateway_reference', $reference)
            ->where('payment_method', $gateway)
            ->firstOrFail();

        // Verify with gateway if needed (optional)
        // $this->verifyWithGateway($payment, $gateway);

        // Process payment if not already paid
        if ($payment->status !== 'paid') {
            $payment->status = 'paid';
            $payment->save();

            $user = $payment->user;
            if ($user) {
                $user->payment_status = 'paid';
                $user->activated_at = now();
                $user->status = 'active';
                $user->package_id = $payment->package_id;
                $user->save();

                // Distribute PV and bonuses
                $this->mlmService->distributeForPayment($payment);
            }
        }

        return redirect()->route('login')
            ->with('success', 'Payment successful! Your account is now active. Please login.');
    }
}