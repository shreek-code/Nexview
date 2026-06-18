<?php

namespace App\Policies;

use App\Models\User;

abstract class OrganizationPolicy
{
    /**
     * Check if the user and the model belong to the same organization.
     */
    protected function belongsToSameOrg(User $user, $model): bool
    {
        return $user->organization_id === $model->organization_id;
    }

    /**
     * Check if the user is a platform admin.
     */
    protected function isPlatformAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if the user has access to view/modify the model.
     * This acts as a common base authorization check.
     */
    protected function hasAccess(User $user, $model): bool
    {
        if (!$this->belongsToSameOrg($user, $model)) {
            return false;
        }

        if ($this->isPlatformAdmin($user)) {
            return true;
        }

        return false; // Subclasses can override or extend this logic for managers
    }
}
