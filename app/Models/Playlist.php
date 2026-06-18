<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasRouteUuid;
use App\Traits\BelongsToOrganization;

class Playlist extends Model
{
    use BelongsToOrganization, HasRouteUuid;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'loop_mode',
        'default_image_duration',
        'transition_effect',
    ];

    public function items()
    {
        return $this->hasMany(PlaylistItem::class)->orderBy('sort_order');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
