<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\PickupCenter;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->where('is_active', true)
            ->get();

        return response()->json($states);
    }

    public function getPickupCenters($stateId)
    {
        $centers = PickupCenter::where('state_id', $stateId)
            ->where('is_active', true)
            ->get();

        return response()->json($centers);
    }
}