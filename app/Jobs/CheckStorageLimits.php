<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckStorageLimits implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(\App\Services\BillingService $billingService, \App\Services\NotificationService $notificationService): void
    {
        $organizations = \App\Models\Organization::all();

        foreach ($organizations as $org) {
            $planLimitBytes = $billingService->getStorageLimit($org);
            if ($planLimitBytes <= 0) continue; // Unlimited or unknown

            $currentUsage = $org->mediaAssets()->sum('size');
            $usagePercent = ($currentUsage / $planLimitBytes) * 100;

            if ($usagePercent >= 90) {
                $notificationService->notifyStorageWarning($org, (int) $usagePercent);
            }
        }
    }
}
