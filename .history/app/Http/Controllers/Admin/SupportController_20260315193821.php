<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $supports = Support::when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.supports.index', compact('supports'));
    }

    public function create()
    {
        return view('admin.supports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'phone'   => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
        ]);

        Support::create($validated);

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support entry created successfully.');
    }

    public function show(Support $support)
    {
        return view('admin.supports.show', compact('support'));
    }

    public function edit(Support $support)
    {
        return view('admin.supports.edit', compact('support'));
    }

    public function update(Request $request, Support $support)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'phone'   => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
        ]);

        $support->update($validated);

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support entry updated successfully.');
    }

    public function destroy(Support $support)
    {
        $support->delete();

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support entry deleted successfully.');
    }
}