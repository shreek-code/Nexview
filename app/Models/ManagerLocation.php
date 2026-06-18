<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ManagerLocation extends Pivot
{
    protected $table = 'manager_location';
    
    public $timestamps = false; // We use assigned_at instead

    protected $fillable = [
        'user_id',
        'location_id',
        'assigned_at',
        'assigned_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
