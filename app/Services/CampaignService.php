<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Location;
use App\Models\Screen;
use App\Models\Playlist;
use App\Models\MediaAsset;
use App\Models\PlaylistItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CampaignService
{
    /**
     * Create a new campaign and handle target resolution and on-the-fly playlist creation.
     */
    public function createCampaign(User $user, array $data): Campaign
    {
        return DB::transaction(function () use ($user, $data) {
            $organizationId = $user->organization_id;
            
            // 1. Resolve Targets
            $targetData = $this->resolveTargets($user, $data['target_type'], $data['location_ids'] ?? [], $data['screen_ids'] ?? []);
            
            // 2. Resolve Content
            $playlistId = $this->resolveContent($organizationId, $data['name'], $data['content_type'], $data['playlist_id'] ?? null, $data['selectedMedia'] ?? []);

            // 3. Create Campaign
            $campaign = Campaign::create([
                'organization_id' => $organizationId,
                'name' => $data['name'],
                'status' => 'active',
                'priority' => 0,
                'content_type' => 'playlist',
                'playlist_id' => $playlistId,
                'target_type' => $data['target_type'],
                'target_location_id' => $targetData['targetLocationId'],
            ]);

            // 4. Attach Screens
            $campaign->screens()->attach($targetData['screenIdsToAttach']);

            broadcast(new \App\Events\CampaignPublished($campaign));

            return $campaign;
        });
    }

    /**
     * Update an existing campaign.
     */
    public function updateCampaign(Campaign $campaign, User $user, array $data): Campaign
    {
        return DB::transaction(function () use ($campaign, $user, $data) {
            $organizationId = $user->organization_id;

            // 1. Resolve Targets
            $targetData = $this->resolveTargets($user, $data['target_type'], $data['location_ids'] ?? [], $data['screen_ids'] ?? []);
            
            // 2. Resolve Content
            $playlistId = $this->resolveContent($organizationId, $data['name'], $data['content_type'], $data['playlist_id'] ?? null, $data['selectedMedia'] ?? []);

            // 3. Update Campaign
            $campaign->update([
                'name' => $data['name'],
                'status' => $data['status'] ?? $campaign->status,
                'content_type' => 'playlist',
                'playlist_id' => $playlistId,
                'target_type' => $data['target_type'],
                'target_location_id' => $targetData['targetLocationId'],
            ]);

            // 4. Sync Screens
            $campaign->screens()->sync($targetData['screenIdsToAttach']);

            broadcast(new \App\Events\CampaignUpdated($campaign));

            return $campaign;
        });
    }

    /**
     * Delete an existing campaign.
     */
    public function deleteCampaign(Campaign $campaign): void
    {
        broadcast(new \App\Events\CampaignDeleted($campaign));
        $campaign->delete();
    }

    /**
     * Validate and resolve the targets to screens.
     */
    protected function resolveTargets(User $user, string $targetType, array $locationIds, array $screenIds): array
    {
        $organizationId = $user->organization_id;
        $managerLocationIds = $user->role === 'manager' ? $user->locations()->pluck('locations.id')->toArray() : null;

        $targetLocationId = null;
        $screenIdsToAttach = [];

        if ($targetType === 'location') {
            $allowedLocations = Location::where('organization_id', $organizationId)
                ->when($managerLocationIds !== null, function ($q) use ($managerLocationIds) {
                    $q->whereIn('id', $managerLocationIds);
                })
                ->pluck('id')->toArray();

            if (array_diff($locationIds, $allowedLocations)) {
                abort(403, 'You do not have access to some of these locations.');
            }
            $targetLocationId = count($locationIds) === 1 ? $locationIds[0] : null;
            $screenIdsToAttach = Screen::whereIn('location_id', $locationIds)->pluck('id')->toArray();
        } else {
            $allowedScreens = Screen::whereHas('location', function ($q) use ($organizationId, $managerLocationIds) {
                $q->where('organization_id', $organizationId);
                if ($managerLocationIds !== null) {
                    $q->whereIn('id', $managerLocationIds);
                }
            })->pluck('id')->toArray();

            if (array_diff($screenIds, $allowedScreens)) {
                abort(403, 'You do not have access to some of these screens.');
            }
            $screenIdsToAttach = $screenIds;
        }

        return [
            'targetLocationId' => $targetLocationId,
            'screenIdsToAttach' => $screenIdsToAttach,
        ];
    }

    /**
     * Resolve content type to a playlist ID, creating one if necessary.
     */
    protected function resolveContent(int $organizationId, string $campaignName, string $contentType, ?string $playlistId, array $selectedMedia): int
    {
        if ($contentType === 'existing_playlist') {
            $playlist = Playlist::where('organization_id', $organizationId)->findOrFail($playlistId);
            return $playlist->id;
        } else {
            // Validate media ownership
            $requestedMediaIds = collect($selectedMedia)->pluck('id')->toArray();
            $allowedMediaCount = MediaAsset::where('organization_id', $organizationId)
                ->whereIn('id', $requestedMediaIds)->count();

            if ($allowedMediaCount !== count(array_unique($requestedMediaIds))) {
                abort(403, 'Invalid media selection.');
            }

            // Create Playlist on the fly
            $playlist = Playlist::create([
                'organization_id' => $organizationId,
                'name' => $campaignName . ' Playlist',
            ]);

            foreach ($selectedMedia as $index => $item) {
                PlaylistItem::create([
                    'playlist_id' => $playlist->id,
                    'content_type' => 'media',
                    'media_asset_id' => $item['id'],
                    'sort_order' => $index,
                    'custom_duration' => $item['duration'],
                ]);
            }
            return $playlist->id;
        }
    }
}
