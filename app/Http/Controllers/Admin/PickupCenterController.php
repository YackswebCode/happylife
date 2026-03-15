<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupCenter;
use App\Models\State;
use Illuminate\Http\Request;

class PickupCenterController extends Controller
{
    /**
     * Display a listing of pickup centers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $state_id = $request->input('state_id');

        $centers = PickupCenter::with('state')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            })
            ->when($state_id, function ($query, $state_id) {
                return $query->where('state_id', $state_id);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $states = State::where('is_active', true)->get();

        return view('admin.pickup_centers.index', compact('centers', 'states'));
    }

    /**
     * Show the form for creating a new pickup center.
     */
    public function create()
    {
        $states = State::where('is_active', true)->get();
        return view('admin.pickup_centers.create', compact('states'));
    }

    /**
     * Store a newly created pickup center in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'operating_hours' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        PickupCenter::create($validated);

        return redirect()->route('admin.pickup-centers.index')
            ->with('success', 'Pickup center created successfully.');
    }

    /**
     * Display the specified pickup center.
     */
    public function show(PickupCenter $pickupCenter)
    {
        $pickupCenter->load('state');
        return view('admin.pickup_centers.show', compact('pickupCenter'));
    }

    /**
     * Show the form for editing the specified pickup center.
     */
    public function edit(PickupCenter $pickupCenter)
    {
        $states = State::where('is_active', true)->get();
        return view('admin.pickup_centers.edit', compact('pickupCenter', 'states'));
    }

    /**
     * Update the specified pickup center in storage.
     */
    public function update(Request $request, PickupCenter $pickupCenter)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'operating_hours' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $pickupCenter->update($validated);

        return redirect()->route('admin.pickup-centers.index')
            ->with('success', 'Pickup center updated successfully.');
    }

    /**
     * Remove the specified pickup center from storage.
     */
    public function destroy(PickupCenter $pickupCenter)
    {
        $pickupCenter->delete();

        return redirect()->route('admin.pickup-centers.index')
            ->with('success', 'Pickup center deleted successfully.');
    }
}