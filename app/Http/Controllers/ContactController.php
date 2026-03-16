<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\Support;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // Get support contact info (first record)
        $support = Support::first();
        // Get settings for address and hours
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('landing.contact', compact('support', 'settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Your message has been sent. We\'ll get back to you soon.');
    }
}