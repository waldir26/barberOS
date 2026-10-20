<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barbershop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'slug',
        'logo',
        'telefono',
        'direccion',
        'estado',
        'motivo_estado',
        'suspendida_en',
        'cancelada_en',
    ];

    protected $casts = [
        'suspendida_en' => 'datetime',
        'cancelada_en' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
