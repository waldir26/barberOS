<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'app_nombre' => 'BarberOS',
            'moneda' => 'USD',
            'zona_horaria' => 'America/El_Salvador',
        ];

        foreach ($defaults as $clave => $valor) {
            Setting::set($clave, $valor, null);
        }
    }
}
