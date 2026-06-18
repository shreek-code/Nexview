<?php

namespace App\Livewire\App\Campaigns;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Campaigns')]
class CampaignIndex extends Component
{
    public function delete(Campaign $campaign)
    {
        $organizationId = Auth::user()->organization_id;
        
        if ($campaign->organization_id !== $organizationId) {
            abort(403);
        }

        $campaignService = app(\App\Services\CampaignService::class);
        $campaignService->deleteCampaign($campaign);

        session()->flash('success', 'Campaign deleted successfully.');
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        $query = Campaign::where('organization_id', $organizationId)
            ->with(['targetLocation', 'playlist']);

        if ($managerLocationIds !== null) {
            $query->whereHas('screens', function ($q) use ($managerLocationIds) {
                $q->whereIn('location_id', $managerLocationIds);
            });
        }

        $campaigns = $query->latest()->get();

        return view('livewire.app.campaigns.campaign-index', [
            'campaigns' => $campaigns,
        ]);
    }
}
