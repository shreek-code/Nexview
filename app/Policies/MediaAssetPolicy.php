<?php

namespace App\Policies;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MediaAssetPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MediaAsset $mediaAsset): bool
    {
        return $this->belongsToSameOrg($user, $mediaAsset);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, MediaAsset $mediaAsset): bool
    {
        return $this->belongsToSameOrg($user, $mediaAsset);
    }

    public function delete(User $user, MediaAsset $mediaAsset): bool
    {
        return $this->belongsToSameOrg($user, $mediaAsset);
    }

    public function restore(User $user, MediaAsset $mediaAsset): bool
    {
        return false;
    }

    public function forceDelete(User $user, MediaAsset $mediaAsset): bool
    {
        return false;
    }
}
