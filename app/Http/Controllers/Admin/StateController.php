<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of states.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $states = State::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.states.index', compact('states'));
    }

    /**
     * Show the form for creating a new state.
     */
    public function create()
    {
        return view('admin.states.create');
    }

    /**
     * Store a newly created state in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:states,code',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        // Set default country_id (assuming Nigeria with id = 1)
        // You may change this to a dynamic value if needed
        $validated['country_id'] = 1;

        State::create($validated);

        return redirect()->route('admin.states.index')
            ->with('success', 'State created successfully.');
    }

    /**
     * Display the specified state.
     */
    public function show(State $state)
    {
        return view('admin.states.show', compact('state'));
    }

    /**
     * Show the form for editing the specified state.
     */
    public function edit(State $state)
    {
        return view('admin.states.edit', compact('state'));
    }

    /**
     * Update the specified state in storage.
     */
    public function update(Request $request, State $state)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:states,code,' . $state->id,
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $state->update($validated);

        return redirect()->route('admin.states.index')
            ->with('success', 'State updated successfully.');
    }

    /**
     * Remove the specified state from storage.
     */
    public function destroy(State $state)
    {
        // Check if state has pickup centers
        if ($state->pickupCenters()->exists()) {
            return back()->with('error', 'Cannot delete state with associated pickup centers.');
        }

        $state->delete();

        return redirect()->route('admin.states.index')
            ->with('success', 'State deleted successfully.');
    }
}