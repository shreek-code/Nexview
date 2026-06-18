<?php

namespace App\Livewire\App;

use Livewire\Component;
use App\Models\Location;
use App\Models\Screen;
use App\Models\MediaAsset;
use App\Models\Playlist;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

class SetupWizard extends Component
{
    public function mount()
    {
        $organization = Auth::user()->organization;

        // If direct route is accessed, redirect to dashboard so the wizard appears there as a drawer
        if (request()->routeIs('app.setup.wizard')) {
            if ($organization->is_onboarded) {
                return $this->redirectRoute('app.dashboard', navigate: true);
            }
            return $this->redirectRoute('app.dashboard');
        }
    }

    public function complete()
    {
        $organization = Auth::user()->organization;
        $organization->update(['is_onboarded' => true]);

        return $this->redirectRoute('app.dashboard', navigate: true);
    }

    public function render()
    {
        $organization = Auth::user()->organization;

        if ($organization->is_onboarded) {
            return <<<'HTML'
                <div></div>
            HTML;
        }

        $orgId = $organization->id;

        $hasLocation = Location::where('organization_id', $orgId)->exists();
        $hasScreen = Screen::whereHas('location', function ($query) use ($orgId) {
            $query->where('organization_id', $orgId);
        })->exists();
        $hasMedia = MediaAsset::where('organization_id', $orgId)->exists();
        $hasPlaylist = Playlist::where('organization_id', $orgId)->exists();
        $hasCampaign = Campaign::where('organization_id', $orgId)->exists();

        $progress = [
            'hasLocation' => $hasLocation,
            'hasScreen' => $hasScreen,
            'hasMedia' => $hasMedia,
            'hasPlaylist' => $hasPlaylist,
            'hasCampaign' => $hasCampaign,
        ];

        $completedCount = ($hasLocation ? 1 : 0) + 
                         ($hasScreen ? 1 : 0) + 
                         ($hasMedia ? 1 : 0) + 
                         ($hasPlaylist ? 1 : 0) + 
                         ($hasCampaign ? 1 : 0);

        return view('livewire.app.setup-wizard', [
            'progress' => $progress,
            'completedCount' => $completedCount,
            'isComplete' => $completedCount === 5,
            'organizationName' => $organization->name,
        ]);
    }
}
