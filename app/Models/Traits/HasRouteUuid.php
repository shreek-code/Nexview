<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasRouteUuid
{
    /**
     * Boot the trait to generate UUIDs on creation.
     */
    protected static function bootHasRouteUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
