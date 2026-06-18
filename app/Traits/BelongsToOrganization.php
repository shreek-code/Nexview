<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToOrganization
{
    public static function bootBelongsToOrganization(): void
    {
        static::creating(function ($model) {
            if (auth()->check() && auth()->user() instanceof \App\Models\User && !isset($model->organization_id)) {
                $model->organization_id = auth()->user()->organization_id;
            }
        });

        static::addGlobalScope('organization', function (Builder $builder) {
            if (auth()->hasUser() && auth()->user() instanceof \App\Models\User) {
                $builder->where($builder->getModel()->getTable() . '.organization_id', auth()->user()->organization_id);
            }
        });
    }
}
