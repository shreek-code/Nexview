<?php

namespace App\Livewire\App\Locations;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Add Location')]
class LocationCreate extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:1000')]
    public $address = '';

    #[Validate('required|string')]
    public $timezone = 'UTC';

    public $isWizard = false;

    public function mount()
    {
        $this->isWizard = request()->boolean('wizard');
    }

    public function save()
    {
        $this->validate();

        $this->authorize('create', Location::class);

        $organizationId = Auth::user()->organization_id;

        $location = Location::create([
            'organization_id' => $organizationId,
            'name' => $this->name,
            'address' => $this->address,
            'timezone' => $this->timezone,
        ]);

        if ($this->isWizard) {
            return $this->redirectRoute('app.setup.wizard', ['step' => 'location'], navigate: true);
        }

        session()->flash('success', 'Location created successfully.');

        return $this->redirectRoute('app.locations.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.app.locations.location-create');
    }
}
