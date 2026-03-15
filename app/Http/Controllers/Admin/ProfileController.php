<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'current_password' => 'nullable|required_with:new_password|current_password:admin',
            'new_password'     => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('new_password')) {
            $data['password'] = Hash::make($request->new_password);
        }

        $admin->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}