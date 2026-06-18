<?php

namespace App\Livewire\App\Campaigns;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Campaign;
use App\Models\Location;
use App\Models\Screen;
use App\Models\Playlist;
use App\Models\MediaAsset;
use App\Models\PlaylistItem;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Edit Campaign')]
class CampaignEdit extends Component
{
    public Campaign $campaign;

    public $name = '';
    public $target_type = 'location'; // location or screens
    public $location_ids = [];
    public $screen_ids = [];
    public $content_type = 'existing_playlist'; // new_playlist or existing_playlist
    public $playlist_id = '';
    public $selectedMedia = []; // For new playlist
    public $status = 'active';

    public function mount(Campaign $campaign)
    {
        $organizationId = Auth::user()->organization_id;

        if ($campaign->organization_id !== $organizationId) {
            abort(403);
        }

        $this->campaign = $campaign;
        $this->name = $campaign->name;
        $this->target_type = $campaign->target_type;
        $this->status = $campaign->status;

        if ($campaign->target_type === 'location') {
            $this->location_ids = $campaign->target_location_id ? [$campaign->target_location_id] : [];
        } else {
            $this->screen_ids = $campaign->screens()->pluck('screens.id')->toArray();
        }

        // We usually just edit the assigned playlist in edit mode,
        // but we'll support both for consistency with create
        $this->content_type = 'existing_playlist';
        $this->playlist_id = $campaign->playlist_id;

        if ($campaign->playlist) {
            $this->selectedMedia = $campaign->playlist->items->map(function ($item) {
                return [
                    'uid' => uniqid(),
                    'id' => $item->media_asset_id,
                    'name' => optional($item->mediaAsset)->name ?? 'Unknown',
                    'type' => optional($item->mediaAsset)->type ?? 'unknown',
                    'file_path' => optional($item->mediaAsset)->file_path ?? '',
                    'duration' => $item->custom_duration
                ];
            })->toArray();
        }
    }

    public function save(\App\Services\CampaignService $campaignService)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'target_type' => 'required|in:location,screens',
            'content_type' => 'required|in:new_playlist,existing_playlist',
            'status' => 'required|in:active,inactive',
        ];

        if ($this->target_type === 'location') {
            $rules['location_ids'] = 'required|array';
            $rules['location_ids.*'] = 'exists:locations,id';
        } else {
            $rules['screen_ids'] = 'required|array';
            $rules['screen_ids.*'] = 'exists:screens,id';
        }

        if ($this->content_type === 'existing_playlist') {
            $rules['playlist_id'] = 'required|exists:playlists,id';
        } else {
            $rules['selectedMedia'] = 'required|array|min:1';
            $rules['selectedMedia.*.id'] = 'required|exists:media_assets,id';
            $rules['selectedMedia.*.duration'] = 'required|integer|min:1';
        }

        $this->validate($rules);

        $this->authorize('update', $this->campaign);

        $campaignService->updateCampaign($this->campaign, Auth::user(), [
            'name' => $this->name,
            'status' => $this->status,
            'target_type' => $this->target_type,
            'location_ids' => $this->location_ids,
            'screen_ids' => $this->screen_ids,
            'content_type' => $this->content_type,
            'playlist_id' => $this->playlist_id,
            'selectedMedia' => $this->selectedMedia,
        ]);

        session()->flash('success', 'Campaign updated successfully.');
        return $this->redirectRoute('app.campaigns.index', navigate: true);
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

        $screensQuery = Screen::with('location')->whereHas('location', function ($q) use ($organizationId, $managerLocationIds) {
            $q->where('organization_id', $organizationId);
            if ($managerLocationIds !== null) {
                $q->whereIn('id', $managerLocationIds);
            }
        });
        $screens = $screensQuery->orderBy('name')->get();

        $playlists = Playlist::where('organization_id', $organizationId)->latest()->get();
        $media = MediaAsset::where('organization_id', $organizationId)->latest()->get();

        return view('livewire.app.campaigns.campaign-edit', [
            'locations' => $locations,
            'screens' => $screens,
            'playlists' => $playlists,
            'media' => $media,
        ]);
    }
}
