<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbershop_id')->nullable()->constrained('barbershops')->cascadeOnDelete();
            $table->string('clave');
            $table->text('valor')->nullable();
            $table->timestamps();
            $table->unique(['barbershop_id', 'clave']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
