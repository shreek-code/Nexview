<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasRouteUuid;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Screen extends Model
{
    use HasApiTokens, HasRouteUuid;

    protected static function booted()
    {
        static::deleting(function ($screen) {
            // Find campaigns that ONLY target this screen
            $campaignsToDelete = $screen->campaigns()->whereHas('screens', function ($query) use ($screen) {
                // We want campaigns where the count of screens is 1 (which is this screen)
                $query->where('screens.id', '!=', $screen->id);
            }, '=', 0)->get();

            foreach ($campaignsToDelete as $campaign) {
                $campaign->delete();
            }
        });

        static::addGlobalScope('organization', function (Builder $builder) {
            if (auth()->hasUser() && auth()->user() instanceof \App\Models\User) {
                $builder->where('organization_id', auth()->user()->organization_id);
            }
        });
    }

    protected $fillable = [
        'organization_id',
        'location_id',
        'name',
        'device_id',
        'registration_code',
        'registration_code_expires_at',
        'status',
        'player_version',
        'resolution',
        'orientation',
        'default_media_id',
        'volume',
        'is_playing',
        'current_media_id',
        'last_heartbeat_at',
        'last_seen_at',
        'last_ip',
    ];

    protected $casts = [
        'last_heartbeat_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope a query to only include screens accessible by the given manager.
     */
    public function scopeForManager(Builder $query, int $managerId)
    {
        return $query->whereHas('location.managers', function ($q) use ($managerId) {
            $q->where('users.id', $managerId);
        });
    }

    public function defaultMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'default_media_id');
    }

    public function currentMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'current_media_id');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_screen')->withTimestamps();
    }
}
