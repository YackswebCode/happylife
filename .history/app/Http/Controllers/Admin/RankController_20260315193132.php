<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $ranks = Rank::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('level')
            ->paginate(15)
            ->withQueryString();

        return view('admin.ranks.index', compact('ranks'));
    }

    public function create()
    {
        return view('admin.ranks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'level'         => 'required|integer|min:1|unique:ranks,level',
            'required_pv'   => 'required|numeric|min:0',
            'cash_reward'   => 'required|numeric|min:0',
            'other_reward'  => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'is_active'     => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Rank::create($validated);

        return redirect()->route('admin.ranks.index')
            ->with('success', 'Rank created successfully.');
    }

    public function show(Rank $rank)
    {
        return view('admin.ranks.show', compact('rank'));
    }

    public function edit(Rank $rank)
    {
        return view('admin.ranks.edit', compact('rank'));
    }

    public function update(Request $request, Rank $rank)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'level'         => 'required|integer|min:1|unique:ranks,level,' . $rank->id,
            'required_pv'   => 'required|numeric|min:0',
            'cash_reward'   => 'required|numeric|min:0',
            'other_reward'  => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'is_active'     => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $rank->update($validated);

        return redirect()->route('admin.ranks.index')
            ->with('success', 'Rank updated successfully.');
    }

    public function destroy(Rank $rank)
    {
        $rank->delete();

        return redirect()->route('admin.ranks.index')
            ->with('success', 'Rank deleted successfully.');
    }
}