<?php

namespace App\Console\Commands;

use App\Models\Screen;
use Illuminate\Console\Command;

class CheckScreenHeartbeats extends Command
{
    protected $signature = 'screens:check-heartbeats';

    protected $description = 'Mark screens as offline if no heartbeat received within 5 minutes';

    public function handle(): void
    {
        $threshold = now()->subMinutes(5);

        $screens = Screen::where('last_seen_at', '<', $threshold)
            ->where('status', 'online')
            ->with('organization') // Eager load organization for notification
            ->get();

        $count = 0;
        $notificationService = app(\App\Services\NotificationService::class);

        foreach ($screens as $screen) {
            $screen->update(['status' => 'offline']);
            broadcast(new \App\Events\ScreenOffline($screen));
            $notificationService->notifyScreenOffline($screen);
            $count++;
        }

        $this->info("Marked {$count} screens as offline.");
    }
}
