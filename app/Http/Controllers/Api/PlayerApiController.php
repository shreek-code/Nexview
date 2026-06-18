<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Location;
use App\Models\MediaAsset;
use Illuminate\Http\Request;

class PlayerApiController extends Controller
{
    /**
     * Heartbeat endpoint for screens to report they are online and their current status.
     */
    public function heartbeat(Request $request)
    {
        $screen = $request->user();

        $previousStatus = $screen->status;

        $screen->update([
            'last_seen_at' => now(),
            'last_heartbeat_at' => now(),
            'last_ip' => request()->ip(),
            'status' => 'online',
        ]);

        if ($previousStatus === 'offline' || $previousStatus === 'pending') {
            broadcast(new \App\Events\ScreenOnline($screen));
        }

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Sync endpoint for screens to fetch their scheduled content (campaigns, playlists, media).
     */
    public function sync(Request $request)
    {
        $screen = $request->user();

        \Log::info('Sync screen details', [
            'screen_id' => $screen->id ?? null,
            'location_id' => $screen->location_id ?? null,
            'location' => $screen->location ?? null,
            'all_locations' => Location::all()->toArray(),
        ]);

        $campaigns = Campaign::where('organization_id', $screen->location->organization_id)
            ->where('status', 'active')
            ->where(function ($q) use ($screen) {
                $q->where('target_type', 'location')
                    ->where('target_location_id', $screen->location_id)
                    ->orWhere(function ($q2) use ($screen) {
                        $q2->where('target_type', 'screens')
                            ->whereHas('screens', function ($q3) use ($screen) {
                                $q3->where('screens.id', $screen->id);
                            });
                    });
            })
            ->with(['mediaAsset', 'playlist.items.mediaAsset'])
            ->orderBy('priority', 'desc')
            ->get();

        // Format the sync payload according to the API contract
        $payload = $campaigns->map(function ($campaign) {
            $content = [];

            if ($campaign->content_type === 'media' && $campaign->mediaAsset) {
                $content[] = [
                    'id' => $campaign->mediaAsset->id,
                    'type' => $campaign->mediaAsset->type,
                    'url' => asset('storage/'.$campaign->mediaAsset->path),
                    'duration' => $campaign->mediaAsset->duration ?? 10,
                ];
            } elseif ($campaign->content_type === 'playlist' && $campaign->playlist) {
                foreach ($campaign->playlist->items as $item) {
                    if ($item->mediaAsset) {
                        $content[] = [
                            'id' => $item->mediaAsset->id,
                            'type' => $item->mediaAsset->type,
                            'url' => asset('storage/'.$item->mediaAsset->path),
                            'duration' => $item->duration ?? $item->mediaAsset->duration ?? 10,
                            'order' => $item->sort_order,
                        ];
                    }
                }
            }

            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'priority' => $campaign->priority,
                'schedule' => [
                    'date_start' => $campaign->date_start ? $campaign->date_start->toDateString() : null,
                    'date_end' => $campaign->date_end ? $campaign->date_end->toDateString() : null,
                    'time_start' => $campaign->time_start,
                    'time_end' => $campaign->time_end,
                    'recurrence' => $campaign->recurrence,
                ],
                'content' => $content,
            ];
        });

        // Also fetch default media if no campaigns are active
        $defaultMedia = null;
        if ($screen->defaultMedia) {
            $defaultMedia = [
                'id' => $screen->defaultMedia->id,
                'type' => $screen->defaultMedia->type,
                'url' => asset('storage/'.$screen->defaultMedia->path),
                'duration' => $screen->defaultMedia->duration ?? 10,
            ];
        }

        return response()->json([
            'screen_id' => $screen->id,
            'device_id' => $screen->device_id,
            'timestamp' => now()->toIso8601String(),
            'default_media' => $defaultMedia,
            'volume' => $screen->volume,
            'is_playing' => $screen->is_playing,
            'current_media_id' => $screen->current_media_id,
            'campaigns' => $payload,
        ]);
    }

    /**
     * Download or retrieve URL for a specific media asset.
     */
    public function media(Request $request, $id)
    {
        $screen = $request->user();

        $mediaAsset = MediaAsset::where('id', $id)
            ->where('organization_id', $screen->location->organization_id)
            ->first();

        if (! $mediaAsset) {
            return response()->json(['error' => 'Media asset not found or unauthorized.'], 404);
        }

        // Return a signed URL or direct path
        // For local storage, we can just return the storage URL or binary
        $url = asset('storage/'.$mediaAsset->path);

        return response()->json([
            'id' => $mediaAsset->id,
            'url' => $url,
            'hash' => md5_file(storage_path('app/public/'.$mediaAsset->path)),
            'size' => $mediaAsset->size,
            'type' => $mediaAsset->type,
        ]);
    }

    /**
     * Report current playback status and hardware metrics.
     */
    public function status(Request $request)
    {
        $validated = $request->validate([
            'playing_media_id' => 'nullable|integer',
            'current_campaign_id' => 'nullable|integer',
            'disk_space_free' => 'nullable|integer',
            'memory_usage_percent' => 'nullable|integer|min:0|max:100',
            'cpu_usage_percent' => 'nullable|integer|min:0|max:100',
            'player_version' => 'nullable|string',
        ]);

        $screen = $request->user();

        $previousStatus = $screen->status;

        // Update basic heartbeat info implicitly since they reported status
        $screen->update([
            'last_heartbeat_at' => now(),
            'last_ip' => request()->ip(),
            'player_version' => $request->player_version ?? $screen->player_version,
            'status' => 'online',
        ]);

        if ($previousStatus === 'offline' || $previousStatus === 'pending') {
            broadcast(new \App\Events\ScreenOnline($screen));
        }

        // In a production scenario, we might store the detailed metrics in a `screen_metrics` table
        // or a JSON column on the screen table. For now, we return a success DTO.

        return response()->json([
            'status' => 'success',
            'message' => 'Status reported successfully',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
    /**
     * Ingest batch playback analytics logs from the player.
     */
    public function analytics(Request $request, \App\Services\AnalyticsService $analyticsService)
    {
        $validated = $request->validate([
            'logs' => 'required|array',
            'logs.*.media_asset_id' => 'required|integer',
            'logs.*.campaign_id' => 'nullable|integer',
            'logs.*.duration_seconds' => 'required|integer',
            'logs.*.played_at' => 'required|date',
        ]);

        $screen = $request->user();

        $previousStatus = $screen->status;

        // Update heartbeat implicitly
        $screen->update([
            'last_seen_at' => now(),
            'last_heartbeat_at' => now(),
            'last_ip' => request()->ip(),
            'status' => 'online',
        ]);

        if ($previousStatus === 'offline' || $previousStatus === 'pending') {
            broadcast(new \App\Events\ScreenOnline($screen));
        }

        $analyticsService->ingestPlaybackLogs($screen, $validated['logs']);

        return response()->json([
            'status' => 'success',
            'message' => 'Analytics ingested successfully',
            'processed_count' => count($validated['logs']),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
