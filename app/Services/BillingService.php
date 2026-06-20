<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Validation\ValidationException;

class BillingService
{
    /**
     * @throws ValidationException
     */
    public function enforceScreenLimit(Organization $organization): void
    {
        $subscription = $organization->subscription()->where('status', 'active')->first();
        
        if (!$subscription || !$subscription->plan) {
            $maxScreens = 1; 
        } else {
            // Uses the getMaxScreensAttribute accessor on Plan model
            $maxScreens = $subscription->plan->max_screens;
        }

        $currentScreens = $organization->screens()->count();

        if ($currentScreens >= $maxScreens) {
            $displayLimit = $maxScreens >= PHP_INT_MAX ? 'unlimited' : $maxScreens;
            throw ValidationException::withMessages([
                'plan' => "You have reached the maximum number of screens ({$displayLimit}) allowed on your current plan.",
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    public function enforceStorageLimit(Organization $organization, int $newFileSizeBytes): void
    {
        $subscription = $organization->subscription()->where('status', 'active')->first();
        
        if (!$subscription || !$subscription->plan) {
            $maxStorageBytes = 104857600; // 100MB default
        } else {
            // Uses the getMaxStorageBytesAttribute accessor on Plan model
            $maxStorageBytes = $subscription->plan->max_storage_bytes;
        }

        $currentStorageBytes = $organization->mediaAssets()->sum('size');

        if (($currentStorageBytes + $newFileSizeBytes) > $maxStorageBytes) {
            $limitDisplay = number_format($maxStorageBytes / 1073741824, 1) . ' GB';
            throw ValidationException::withMessages([
                'plan' => "Uploading this file would exceed your plan's storage limit ({$limitDisplay}).",
            ]);
        }
    }

    public function getStorageLimit(Organization $organization): int
    {
        $subscription = $organization->subscription()->where('status', 'active')->first();
        
        if (!$subscription || !$subscription->plan) {
            return 104857600; // 100MB default
        }

        // Uses the getMaxStorageBytesAttribute accessor on Plan model
        return (int) $subscription->plan->max_storage_bytes;
    }

    public function getScreenLimit(Organization $organization): int
    {
        $subscription = $organization->subscription()->where('status', 'active')->first();
        
        if (!$subscription || !$subscription->plan) {
            return 1;
        }

        return (int) $subscription->plan->max_screens;
    }
}
