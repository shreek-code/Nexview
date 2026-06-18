<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Screen;
use App\Models\User;
use App\Notifications\OfflineScreenAlert;
use App\Notifications\StorageWarning;

class NotificationService
{
    /**
     * Notify an organization about an offline screen.
     */
    public function notifyScreenOffline(Screen $screen)
    {
        $organization = $screen->organization;
        
        // Notify the admin
        $admins = User::where('organization_id', $organization->id)->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OfflineScreenAlert($screen));
        }

        // Notify managers associated with the location
        if ($screen->location_id) {
            $managers = User::where('organization_id', $organization->id)
                ->where('role', 'manager')
                ->whereHas('locations', function ($query) use ($screen) {
                    $query->where('locations.id', $screen->location_id);
                })->get();
                
            foreach ($managers as $manager) {
                $manager->notify(new OfflineScreenAlert($screen));
            }
        }
    }

    /**
     * Notify an organization about storage limit warnings.
     */
    public function notifyStorageWarning(Organization $organization, int $usagePercent)
    {
        $admins = User::where('organization_id', $organization->id)->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new StorageWarning($organization, $usagePercent));
        }
    }
}
