<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasRouteUuid;
use App\Traits\BelongsToOrganization;

class Campaign extends Model
{
    use BelongsToOrganization, HasRouteUuid;

    protected $fillable = [
        'organization_id',
        'name',
        'status',
        'priority',
        'content_type',
        'playlist_id',
        'media_asset_id',
        'widget_id',
        'target_type',
        'target_location_id',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'recurrence',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function mediaAsset()
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function targetLocation()
    {
        return $this->belongsTo(Location::class, 'target_location_id');
    }

    public function screens()
    {
        return $this->belongsToMany(Screen::class, 'campaign_screen')->withTimestamps();
    }
}
