<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['barbershop_id', 'clave', 'valor'];

    public static function get(string $clave, $default = null, ?int $barbershopId = null)
    {
        return static::where('clave', $clave)
            ->where('barbershop_id', $barbershopId)
            ->value('valor') ?? $default;
    }

    public static function set(string $clave, $valor, ?int $barbershopId = null): void
    {
        static::updateOrCreate(
            ['clave' => $clave, 'barbershop_id' => $barbershopId],
            ['valor' => $valor]
        );
    }
}
