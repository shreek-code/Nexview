<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasRouteUuid;
use App\Traits\BelongsToOrganization;

class MediaAsset extends Model
{
    use BelongsToOrganization, HasRouteUuid;

    protected $fillable = [
        'organization_id',
        'name',
        'file_path',
        'mime_type',
        'size',
        'type',
        'duration',
        'status',
        'source',
        'derived_from_id',
    ];

    public function getPathAttribute()
    {
        return $this->file_path;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function derivedFrom()
    {
        return $this->belongsTo(MediaAsset::class, 'derived_from_id');
    }

    public function derivatives()
    {
        return $this->hasMany(MediaAsset::class, 'derived_from_id');
    }
}
