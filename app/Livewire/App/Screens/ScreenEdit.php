<?php

namespace App\Livewire\App\Screens;

use App\Models\Location;
use App\Models\Screen;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Edit Screen')]
class ScreenEdit extends Component
{
    public Screen $screen;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|exists:locations,id')]
    public $location_id = '';

    public function mount(Screen $screen)
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        // Authorize
        if ($screen->location->organization_id !== $organizationId) {
            abort(403);
        }

        if ($managerLocationIds !== null && ! in_array($screen->location_id, $managerLocationIds)) {
            abort(403);
        }

        $this->screen = $screen;
        $this->name = $screen->name;
        $this->location_id = $screen->location_id;
    }

    public function save()
    {
        $this->validate();

        $this->authorize('update', $this->screen);

        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        $location = Location::where('organization_id', $organizationId)
            ->when($managerLocationIds !== null, function ($q) use ($managerLocationIds) {
                $q->whereIn('id', $managerLocationIds);
            })
            ->findOrFail($this->location_id);

        $this->screen->update([
            'name' => $this->name,
            'location_id' => $location->id,
        ]);

        session()->flash('success', 'Screen updated successfully.');

        return $this->redirectRoute('app.screens.index', navigate: true);
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

        return view('livewire.app.screens.screen-edit', [
            'locations' => $locations,
        ]);
    }
}
