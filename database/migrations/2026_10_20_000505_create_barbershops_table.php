<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barbershops', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->enum('estado', ['activa', 'suspendida', 'en_prueba', 'cancelada'])->default('en_prueba');
            $table->string('motivo_estado')->nullable();
            $table->timestamp('suspendida_en')->nullable();
            $table->timestamp('cancelada_en')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barbershops');
    }
};
