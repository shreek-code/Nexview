<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;

class PlatformAuditLog extends Model
{
    use BelongsToOrganization;

    public $timestamps = false; // We only have created_at

    protected $fillable = [
        'organization_id',
        'platform_user_id',
        'action',
        'reason',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function platformUser()
    {
        return $this->belongsTo(PlatformUser::class);
    }
}
