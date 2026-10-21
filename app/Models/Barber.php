<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBarbershop;

class Barber extends Model
{
    use SoftDeletes, BelongsToBarbershop;

    protected $fillable = [
        'barbershop_id',
        'nombre',
        'telefono',
        'foto',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
