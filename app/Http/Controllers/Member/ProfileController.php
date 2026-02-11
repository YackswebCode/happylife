<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user()->load(['package', 'rank', 'sponsor', 'placement']);
        
        return view('member.profile.index', compact('user'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('member.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone'   => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'state'   => 'nullable|string|max:100',
            // Password change validation
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password'     => 'nullable|min:8|confirmed',
        ]);

        // Update allowed fields
        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->country = $request->country;
        $user->state   = $request->state;

        // Update password if provided
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('member.profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}