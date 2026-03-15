<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Package;
use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // at the top

class UserController extends Controller
{
    /**
     * Display a listing of users with optional search.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $package = $request->input('package');

        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($package, function ($query, $package) {
                return $query->where('package_id', $package);
            })
            ->with('package')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $packages = Package::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'packages'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('package', 'rank', 'sponsor', 'placement');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $packages = Package::where('is_active', true)->get();
        $ranks = Rank::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'packages', 'ranks'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id',
            'rank_id' => 'nullable|exists:ranks,id',
            'status' => 'required|in:active,inactive,suspended',
            'payment_status' => 'required|in:pending,paid',
            'commission_wallet_balance' => 'nullable|numeric|min:0',
            'registration_wallet_balance' => 'nullable|numeric|min:0',
            'shopping_wallet_balance' => 'nullable|numeric|min:0',
            'rank_wallet_balance' => 'nullable|numeric|min:0',
        ]);

        // Handle password update separately if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->password = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself or other admins? Optional
        if ($user->id === auth()->guard('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status (active/inactive/suspended)
     */
    public function toggleStatus(User $user, $status)
    {
        if (!in_array($status, ['active', 'inactive', 'suspended'])) {
            return back()->with('error', 'Invalid status.');
        }

        $user->update(['status' => $status]);

        return back()->with('success', "User status changed to {$status}.");
    }

 

public function create()
{
    $packages = Package::where('is_active', true)->get();
    $ranks = Rank::where('is_active', true)->get();
    return view('admin.users.create', compact('packages', 'ranks'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'username' => 'required|string|unique:users|alpha_dash',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'gender' => 'nullable|in:male,female,other',
        'country' => 'nullable|string',
        'state' => 'nullable|string',
        'package_id' => 'nullable|exists:packages,id',
        'rank_id' => 'nullable|exists:ranks,id',
        'status' => 'required|in:active,inactive,suspended',
        'payment_status' => 'required|in:pending,paid',
        'commission_wallet_balance' => 'nullable|numeric|min:0',
        'registration_wallet_balance' => 'nullable|numeric|min:0',
        'shopping_wallet_balance' => 'nullable|numeric|min:0',
        'rank_wallet_balance' => 'nullable|numeric|min:0',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $validated['password'] = Hash::make($validated['password']);
    // Generate referral code (using username or a unique string)
    $validated['referral_code'] = $validated['username']; // you can also use Str::random(8)

    $user = User::create($validated);

    return redirect()->route('admin.users.index')
        ->with('success', 'User created successfully.');
}
}