<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaybackLog extends Model
{
    protected $fillable = [
        'organization_id',
        'screen_id',
        'campaign_id',
        'media_asset_id',
        'duration_seconds',
        'played_at',
    ];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function mediaAsset()
    {
        return $this->belongsTo(MediaAsset::class);
    }
}
