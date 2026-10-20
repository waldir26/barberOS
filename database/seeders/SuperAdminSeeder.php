<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Administrador',
                'email' => null,
                'barbershop_id' => null,
                'password' => Hash::make('Super123!'),
                'activo' => true,
                'debe_cambiar_password' => true,
            ]
        );

        if (!$user->hasRole('superadmin')) {
            $user->assignRole('superadmin');
        }
    }
}
