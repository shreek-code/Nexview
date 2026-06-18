<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_onboarded',
        'phone',
        'industry',
        'company_size',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    public function mediaAssets()
    {
        return $this->hasMany(MediaAsset::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function platformAuditLogs()
    {
        return $this->hasMany(PlatformAuditLog::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
