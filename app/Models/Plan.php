<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'stripe_price_id',
        'payment_model',
        'billing_cycle',
        'remote_access',
        'network_restriction',
        'limits',
        'analytics',
        'widgets',
        'broadcasts',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'remote_access' => 'boolean',
        'limits' => 'array',
        'analytics' => 'array',
        'widgets' => 'array',
        'broadcasts' => 'array',
    ];
}
