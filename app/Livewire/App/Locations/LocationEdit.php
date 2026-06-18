<?php

namespace App\Livewire\App\Locations;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Edit Location')]
class LocationEdit extends Component
{
    public Location $location;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:1000')]
    public $address = '';

    #[Validate('required|string')]
    public $timezone = 'UTC';

    public function mount(Location $location)
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        // Authorize
        if ($location->organization_id !== $organizationId) {
            abort(403);
        }

        if ($managerLocationIds !== null && ! in_array($location->id, $managerLocationIds)) {
            abort(403);
        }

        $this->location = $location;
        $this->name = $location->name;
        $this->address = $location->address;
        $this->timezone = $location->timezone;
    }

    public function save()
    {
        $this->validate();

        $this->authorize('update', $this->location);

        $this->location->update([
            'name' => $this->name,
            'address' => $this->address,
            'timezone' => $this->timezone,
        ]);

        session()->flash('success', 'Location updated successfully.');

        return $this->redirectRoute('app.locations.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.app.locations.location-edit');
    }
}
