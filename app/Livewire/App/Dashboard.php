<?php

namespace App\Livewire\App;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Screen;
use App\Models\Location;
use App\Models\Campaign;
use App\Models\MediaAsset;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function getListeners(): array
    {
        $orgId = Auth::user()?->organization_id;
        if (! $orgId) return [];

        return [
            "echo-private:organization.{$orgId},.screen.updated" => '$refresh',
            "echo-private:organization.{$orgId},.screen.online" => '$refresh',
            "echo-private:organization.{$orgId},.screen.offline" => '$refresh',
            "echo-private:organization.{$orgId},.campaign.published" => '$refresh',
        ];
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $managerLocationIds = Auth::user()->role === 'manager' ? Auth::user()->locations()->pluck('locations.id')->toArray() : null;

        // Location scoping
        $locationQuery = Location::where('organization_id', $organizationId);
        if ($managerLocationIds !== null) {
            $locationQuery->whereIn('id', $managerLocationIds);
        }
        $locationIds = $locationQuery->pluck('id');

        // Screens stats
        $screens = Screen::whereIn('location_id', $locationIds)->get();
        $totalScreens = $screens->count();
        $onlineScreens = $screens->where('status', 'online')->count();
        $offlineScreens = $screens->where('status', 'offline')->count();
        // Degraded: marked 'online' but hasn't pinged in 5 minutes
        $degradedScreens = $screens->where('status', 'online')
            ->filter(function ($screen) {
                return $screen->last_seen_at && $screen->last_seen_at->lt(now()->subMinutes(5));
            })->count();

        // Active Campaigns
        $campaignQuery = Campaign::where('organization_id', $organizationId)->where('status', 'active');
        if ($managerLocationIds !== null) {
            $campaignQuery->whereHas('screens', function ($q) use ($managerLocationIds) {
                $q->whereIn('location_id', $managerLocationIds);
            });
        }
        $activeCampaigns = $campaignQuery->count();

        // Media Assets (Organization-wide content)
        $mediaAssets = MediaAsset::where('organization_id', $organizationId)->count();

        // Live Feed: 5 most recently active screens
        $liveFeed = Screen::whereIn('location_id', $locationIds)
            ->with('location')
            ->orderBy('last_seen_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($screen) {
                return [
                    'name' => $screen->name,
                    'loc' => $screen->location ? $screen->location->name : 'Unknown Location',
                    'status' => $screen->status === 'online' ? 'Online' : 'Offline',
                    'color' => $screen->status === 'online' ? 'success' : 'error'
                ];
            })->values()->toArray();

        return view('livewire.app.dashboard', [
            'stats' => [
                'total_screens' => $totalScreens,
                'online_screens' => $onlineScreens,
                'offline_screens' => $offlineScreens,
                'degraded_screens' => $degradedScreens,
                'active_campaigns' => $activeCampaigns,
                'media_assets' => $mediaAssets,
            ],
            'liveFeed' => $liveFeed,
        ]);
    }
}
