<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLocation extends Model
{
    protected $table = 'campaign_locations';

    protected $fillable = [
        'campaign_id',
        'location_id',
    ];
}
