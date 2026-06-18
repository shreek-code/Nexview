<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistItem extends Model
{
    protected $fillable = [
        'playlist_id',
        'content_type',
        'media_asset_id',
        'widget_id',
        'sort_order',
        'custom_duration',
    ];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function mediaAsset()
    {
        return $this->belongsTo(MediaAsset::class);
    }
}
