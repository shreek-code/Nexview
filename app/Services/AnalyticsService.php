<?php

namespace App\Services;

use App\Models\PlaybackLog;
use App\Models\Screen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Ingest a batch of playback logs from a screen.
     */
    public function ingestPlaybackLogs(Screen $screen, array $logs): void
    {
        $organizationId = $screen->organization_id;
        $insertData = [];

        foreach ($logs as $log) {
            $insertData[] = [
                'organization_id' => $organizationId,
                'screen_id' => $screen->id,
                'campaign_id' => $log['campaign_id'] ?? null,
                'media_asset_id' => $log['media_asset_id'],
                'duration_seconds' => $log['duration_seconds'] ?? 0,
                'played_at' => Carbon::parse($log['played_at'])->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insertData)) {
            // Batch insert for performance
            foreach (array_chunk($insertData, 500) as $chunk) {
                PlaybackLog::insert($chunk);
            }
        }
    }
}
