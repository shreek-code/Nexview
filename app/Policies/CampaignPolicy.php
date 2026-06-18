<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CampaignPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        if ($this->hasAccess($user, $campaign)) {
            return true;
        }

        // Manager check: can view if it targets any screen they manage
        $managerLocationIds = $user->locations()->pluck('locations.id')->toArray();
        return $campaign->screens()->whereIn('location_id', $managerLocationIds)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Campaign $campaign): bool
    {
        if ($this->hasAccess($user, $campaign)) {
            return true;
        }

        // Manager check: can only edit if ALL screens it targets are managed by them
        $managerLocationIds = $user->locations()->pluck('locations.id')->toArray();
        $unmanagedScreensCount = $campaign->screens()->whereNotIn('location_id', $managerLocationIds)->count();
        
        return $unmanagedScreensCount === 0;
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $this->update($user, $campaign);
    }

    public function restore(User $user, Campaign $campaign): bool
    {
        return false;
    }

    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return false;
    }
}
