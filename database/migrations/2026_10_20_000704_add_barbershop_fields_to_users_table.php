<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('barbershop_id')->nullable()->after('id')
                ->constrained('barbershops')->nullOnDelete();
            $table->string('username')->unique()->after('barbershop_id');
            $table->boolean('activo')->default(true)->after('password');
            $table->boolean('debe_cambiar_password')->default(true)->after('activo');
            $table->timestamp('ultimo_login')->nullable()->after('debe_cambiar_password');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['barbershop_id']);
            $table->dropColumn([
                'barbershop_id',
                'username',
                'activo',
                'debe_cambiar_password',
                'ultimo_login',
                'deleted_at',
            ]);
        });
    }
};
