<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBarbershop;

class Transaction extends Model
{
    use SoftDeletes, BelongsToBarbershop;

    protected $fillable = [
        'barbershop_id',
        'tipo',
        'descripcion',
        'monto',
        'appointment_id'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
