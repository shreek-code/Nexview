<?php

namespace App\Services;

use App\Models\Playlist;
use App\Models\PlaylistItem;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PlaylistService
{
    /**
     * Create a new playlist with the provided media items.
     */
    public function createPlaylist(User $user, array $data): Playlist
    {
        return DB::transaction(function () use ($user, $data) {
            $organizationId = $user->organization_id;

            // Validate media ownership
            $requestedMediaIds = collect($data['selectedMedia'])->pluck('id')->toArray();
            $allowedMediaCount = MediaAsset::where('organization_id', $organizationId)
                ->whereIn('id', $requestedMediaIds)->count();

            if ($allowedMediaCount !== count(array_unique($requestedMediaIds))) {
                abort(403, 'Invalid media selection.');
            }

            $playlist = Playlist::create([
                'organization_id' => $organizationId,
                'name' => $data['name'],
            ]);

            foreach ($data['selectedMedia'] as $index => $item) {
                PlaylistItem::create([
                    'playlist_id' => $playlist->id,
                    'content_type' => 'media',
                    'media_asset_id' => $item['id'],
                    'sort_order' => $index,
                    'custom_duration' => $item['duration'],
                ]);
            }

            return $playlist;
        });
    }

    /**
     * Update an existing playlist and its media items.
     */
    public function updatePlaylist(Playlist $playlist, User $user, array $data): Playlist
    {
        return DB::transaction(function () use ($playlist, $user, $data) {
            $organizationId = $user->organization_id;

            // Validate media ownership
            $requestedMediaIds = collect($data['selectedMedia'])->pluck('id')->toArray();
            $allowedMediaCount = MediaAsset::where('organization_id', $organizationId)
                ->whereIn('id', $requestedMediaIds)->count();

            if ($allowedMediaCount !== count(array_unique($requestedMediaIds))) {
                abort(403, 'Invalid media selection.');
            }

            $playlist->update([
                'name' => $data['name'],
            ]);

            // Sync items by deleting existing and recreating to preserve sorting
            $playlist->items()->delete();

            foreach ($data['selectedMedia'] as $index => $item) {
                PlaylistItem::create([
                    'playlist_id' => $playlist->id,
                    'content_type' => 'media',
                    'media_asset_id' => $item['id'],
                    'sort_order' => $index,
                    'custom_duration' => $item['duration'],
                ]);
            }

            return $playlist;
        });
    }
}
