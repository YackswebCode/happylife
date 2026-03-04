<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'package_id' => 'required|exists:packages,id',
            'sponsor_username' => 'nullable|exists:users,username',
            'placement_username' => 'nullable|exists:users,username',
            'placement_position' => 'nullable|in:left,right',
        ]);

        DB::beginTransaction();

        try {

            $sponsor = null;
            if ($request->sponsor_username) {
                $sponsor = User::where('username', $request->sponsor_username)->first();
            }

            $placement = null;
            if ($request->placement_username) {
                $placement = User::where('username', $request->placement_username)->first();
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'package_id' => $request->package_id,
                'sponsor_id' => $sponsor?->id,
                'placement_id' => $placement?->id,
                'placement_position' => $request->placement_position,
                'status' => 'inactive',
                'payment_status' => 'pending',
                'registration_date' => now(),
            ]);

            DB::commit();

            return redirect()->route('payment')
                ->with('success', 'Registration successful. Please complete payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed.']);
        }
    }
}