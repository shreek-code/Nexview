<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlaylistPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Playlist $playlist): bool
    {
        return $this->belongsToSameOrg($user, $playlist);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Playlist $playlist): bool
    {
        return $this->belongsToSameOrg($user, $playlist);
    }

    public function delete(User $user, Playlist $playlist): bool
    {
        return $this->belongsToSameOrg($user, $playlist);
    }

    public function restore(User $user, Playlist $playlist): bool
    {
        return false;
    }

    public function forceDelete(User $user, Playlist $playlist): bool
    {
        return false;
    }
}
