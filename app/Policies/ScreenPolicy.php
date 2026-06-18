<?php

namespace App\Policies;

use App\Models\Screen;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScreenPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Screen $screen): bool
    {
        if ($this->hasAccess($user, $screen)) {
            return true;
        }

        // Managers can view if they manage the screen's location
        if ($user->role === 'manager' && $screen->location_id) {
            return $user->locations()->where('locations.id', $screen->location_id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return true; // Location assignment check handled in controller/form request
    }

    public function update(User $user, Screen $screen): bool
    {
        if (!$this->view($user, $screen)) {
            return false;
        }
        
        return $this->passesNetworkRestriction($user, $screen);
    }

    public function delete(User $user, Screen $screen): bool
    {
        if (!$this->view($user, $screen)) {
            return false;
        }
        
        return $this->passesNetworkRestriction($user, $screen);
    }

    protected function passesNetworkRestriction(User $user, Screen $screen): bool
    {
        $plan = $user->organization->plan;
        
        if ($plan && $plan->network_restriction === 'same_network_only') {
            if ($screen->last_ip && request()->ip() !== $screen->last_ip) {
                return false;
            }
        }
        
        return true;
    }

    public function restore(User $user, Screen $screen): bool
    {
        return false;
    }

    public function forceDelete(User $user, Screen $screen): bool
    {
        return false;
    }
}
