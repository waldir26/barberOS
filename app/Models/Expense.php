<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBarbershop;

class Expense extends Model
{
    use SoftDeletes, BelongsToBarbershop;

    protected $fillable = [
        'barbershop_id',
        'descripcion',
        'monto',
        'fecha'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'date',
    ];
}
