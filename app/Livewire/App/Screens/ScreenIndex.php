<?php

namespace App\Livewire\App\Screens;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Screen;
use App\Models\MediaAsset;
use App\Services\ScreenService;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Screens')]
class ScreenIndex extends Component
{
    public function getListeners(): array
    {
        $orgId = Auth::user()?->organization_id;
        if (! $orgId) return [];

        return [
            "echo-private:organization.{$orgId},.screen.updated" => '$refresh',
            "echo-private:organization.{$orgId},.screen.online" => '$refresh',
            "echo-private:organization.{$orgId},.screen.offline" => '$refresh',
        ];
    }

    public $showAddModal = false;
    public $registration_code = '';
    public $name = '';
    public $location_id = '';

    public $connectionError = null;

    public function mount()
    {
        if (request()->query('add')) {
            $this->showAddModal = true;
        }
    }

    public function openAddModal()
    {
        $this->reset(['registration_code', 'name', 'location_id', 'connectionError']);
        $this->showAddModal = true;
        $this->dispatch('open-modal', 'add-screen-modal');
    }

    public function connectScreen()
    {
        $this->connectionError = null;
        
        $this->validate([
            'registration_code' => 'required|string|size:6',
            'name' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
        ]);

        $screenService = app(\App\Services\ScreenService::class);
        $billingService = app(\App\Services\BillingService::class);
        try {
            $billingService->enforceScreenLimit(Auth::user()->organization);

            $screenService->provisionScreen(Auth::user(), [
                'name' => $this->name,
                'location_id' => $this->location_id,
                'registration_code' => $this->registration_code,
            ]);

            $this->showAddModal = false;
            $this->dispatch('close-modal', 'add-screen-modal');
            session()->flash('success', 'Screen paired successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->connectionError = collect($e->errors())->flatten()->first();
        } catch (\Exception $e) {
            $this->connectionError = 'An error occurred: ' . $e->getMessage();
        }
    }

    public function delete(Screen $screen)
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        if ($screen->organization_id !== $organizationId) {
            abort(403);
        }

        if ($managerLocationIds !== null && $screen->location_id && !in_array($screen->location_id, $managerLocationIds)) {
            abort(403);
        }

        $screen->delete();
        session()->flash('success', 'Screen deleted successfully.');
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        $query = Screen::with('location')->where('organization_id', $organizationId);

        if ($managerLocationIds !== null) {
            // Managers can only see screens in their locations
            $query->whereIn('location_id', $managerLocationIds);
        }

        $screens = $query->orderBy('name')->get();
        $media = MediaAsset::where('organization_id', $organizationId)->latest()->get();

        $locationsQuery = \App\Models\Location::where('organization_id', $organizationId);
        if ($managerLocationIds !== null) {
            $locationsQuery->whereIn('id', $managerLocationIds);
        }
        $locations = $locationsQuery->orderBy('name')->get();

        return view('livewire.app.screens.screen-index', [
            'screens' => $screens,
            'media' => $media,
            'locations' => $locations,
        ]);
    }

    public function setDefaultMedia($screenId, $mediaId, ScreenService $screenService)
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        $screen = Screen::findOrFail($screenId);

        if ($screen->organization_id !== $organizationId) {
            abort(403);
        }

        if ($managerLocationIds !== null && $screen->location_id && !in_array($screen->location_id, $managerLocationIds)) {
            abort(403);
        }

        $screenService->updatePlaybackSettings($screen, ['default_media_id' => $mediaId]);
        session()->flash('success', 'Default media updated successfully.');
    }
}
