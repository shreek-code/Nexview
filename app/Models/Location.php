<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasRouteUuid;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToOrganization;

class Location extends Model
{
    use BelongsToOrganization, HasRouteUuid;

    protected $fillable = [
        'organization_id',
        'name',
        'timezone',
        'default_media_id',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'manager_location', 'location_id', 'user_id')
                    ->withPivot('assigned_at', 'assigned_by');
    }

    public function screens(): HasMany
    {
        return $this->hasMany(Screen::class);
    }

    protected static function booted()
    {
        static::deleting(function ($location) {
            $location->campaigns()->delete();
        });
    }

    public function defaultMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'default_media_id');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'target_location_id');
    }
}
