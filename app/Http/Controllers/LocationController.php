<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(Request $request, \App\Services\LocationService $locationService)
    {
        $this->authorize('create', Location::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'timezone' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        $locationService->createLocation(Auth::user()->organization, $validated);

        return redirect()->route('app.locations.index');
    }
}
