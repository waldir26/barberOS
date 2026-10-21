<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbershop_id')->constrained()->cascadeOnDelete();
            $table->string('codigo')->unique(); // Código de barras o SKU
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->string('foto')->nullable();
            $table->decimal('precio', 10, 2); // Precio de venta
            $table->integer('stock')->default(0); // Cantidad actual
            $table->integer('stock_minimo')->default(5); // Alerta de reabastecimiento
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
