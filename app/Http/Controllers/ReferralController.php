<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ReferralController extends Controller
{
    public function check($code)
    {
        try {
            $user = User::where('referral_code', $code)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid referral code'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Referral code valid',
                'name' => $user->name
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}