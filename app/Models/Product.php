<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBarbershop;

class Product extends Model
{
    use SoftDeletes, BelongsToBarbershop;

    protected $fillable = [
        'barbershop_id',
        'codigo',
        'nombre',
        'marca',
        'foto',
        'precio',
        'stock',
        'stock_minimo',
        'activo'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];
}
