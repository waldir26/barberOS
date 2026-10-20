<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToBarbershop
{
    protected static function bootBelongsToBarbershop(): void
    {
        static::addGlobalScope('barbershop', function (Builder $builder) {
            if (auth()->check() && auth()->user()->barbershop_id !== null) {
                $builder->where($builder->getModel()->getTable() . '.barbershop_id', auth()->user()->barbershop_id);
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->barbershop_id !== null && empty($model->barbershop_id)) {
                $model->barbershop_id = auth()->user()->barbershop_id;
            }
        });
    }
}
