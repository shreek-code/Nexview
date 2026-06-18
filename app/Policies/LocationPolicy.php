<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LocationPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Location $location): bool
    {
        if ($this->hasAccess($user, $location)) {
            return true;
        }

        // Managers can view if they manage the location
        return $user->role === 'manager' && $user->locations()->where('locations.id', $location->id)->exists();
    }

    public function create(User $user): bool
    {
        return $this->isPlatformAdmin($user);
    }

    public function update(User $user, Location $location): bool
    {
        return $this->hasAccess($user, $location);
    }

    public function delete(User $user, Location $location): bool
    {
        return $this->update($user, $location);
    }

    public function restore(User $user, Location $location): bool
    {
        return false;
    }

    public function forceDelete(User $user, Location $location): bool
    {
        return false;
    }
}
