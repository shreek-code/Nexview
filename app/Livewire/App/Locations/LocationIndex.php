<?php

namespace App\Livewire\App\Locations;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Locations')]
class LocationIndex extends Component
{
    public function delete(Location $location)
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        // Authorize deletion
        if ($location->organization_id !== $organizationId) {
            abort(403);
        }

        if ($managerLocationIds !== null && !in_array($location->id, $managerLocationIds)) {
            abort(403);
        }

        $location->delete();
        session()->flash('success', 'Location deleted successfully.');
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        $query = Location::where('organization_id', $organizationId);

        if ($managerLocationIds !== null) {
            $query->whereIn('id', $managerLocationIds);
        }

        $locations = $query->orderBy('name')->get();

        return view('livewire.app.locations.location-index', [
            'locations' => $locations,
        ]);
    }
}
