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
            // Assume free tier or no plan means basic limits
            $maxScreens = 1; 
        } else {
            $maxScreens = $subscription->plan->max_screens;
        }

        $currentScreens = $organization->screens()->count();

        if ($currentScreens >= $maxScreens) {
            throw ValidationException::withMessages([
                'plan' => "You have reached the maximum number of screens ({$maxScreens}) allowed on your current plan.",
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
            $maxStorageBytes = $subscription->plan->max_storage_bytes;
        }

        $currentStorageBytes = $organization->mediaAssets()->sum('size');

        if (($currentStorageBytes + $newFileSizeBytes) > $maxStorageBytes) {
            throw ValidationException::withMessages([
                'plan' => "Uploading this file would exceed your plan's storage limit.",
            ]);
        }
    }
    public function getStorageLimit(Organization $organization): int
    {
        $subscription = $organization->subscription()->where('status', 'active')->first();
        
        if (!$subscription || !$subscription->plan) {
            return 104857600; // 100MB default
        }

        return (int) $subscription->plan->max_storage_bytes;
    }
}
