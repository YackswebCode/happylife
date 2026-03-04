<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Country;
use App\Models\State;
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

        // Fetch wallet balances
        $walletBalances = Wallet::where('user_id', $user->id)
            ->pluck('balance', 'type')
            ->toArray();

        $commissionBalance   = $walletBalances['commission'] ?? 0;
        $registrationBalance = $walletBalances['registration'] ?? 0;
        $rankBalance         = $walletBalances['rank'] ?? 0;
        $shoppingBalance     = $walletBalances['shopping'] ?? 0;

        return view('member.profile.index', compact(
            'user',
            'commissionBalance',
            'registrationBalance',
            'rankBalance',
            'shoppingBalance'
        ));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        // All active countries for dropdown
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        // All active states (filtered via JS)
        $states = State::where('is_active', true)->orderBy('name')->get();

        // Find the country ID that matches the user's stored country name (if any)
        $selectedCountryId = null;
        if ($user->country) {
            $country = $countries->firstWhere('name', $user->country);
            $selectedCountryId = $country ? $country->id : null;
        }

        return view('member.profile.edit', compact('user', 'countries', 'states', 'selectedCountryId'));
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
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|exists:countries,id',
            'state'   => 'nullable|exists:states,id',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password'     => 'nullable|min:8|confirmed',
        ]);

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->address = $request->address;

        // Convert selected country ID to country name
        if ($request->filled('country')) {
            $country = Country::find($request->country);
            $user->country = $country ? $country->name : null;
        } else {
            $user->country = null;
        }

        // Convert selected state ID to state name
        if ($request->filled('state')) {
            $state = State::find($request->state);
            $user->state = $state ? $state->name : null;
        } else {
            $user->state = null;
        }

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('member.profile.index')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * AJAX: Get states by country ID.
     */
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(['id', 'name']);
        return response()->json($states);
    }
}