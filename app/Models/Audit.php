<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = [
        'barbershop_id',
        'user_id',
        'accion',
        'modelo',
        'modelo_id',
        'datos',
        'ip',
        'user_agent',
    ];

    protected $casts = ['datos' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
