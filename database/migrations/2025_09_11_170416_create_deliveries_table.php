<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('km_start', 8, 2);
            $table->decimal('km_end', 8, 2);
            $table->integer('delivery_count')->default(1); // Quantidade de entregas
            $table->decimal('price_per_delivery', 8, 2)->default(4.50); // Valor por entrega
            $table->decimal('km_per_liter', 8, 2)->nullable(); // Quilômetros por litro da moto
            $table->decimal('liters', 8, 2)->nullable();
            $table->decimal('price_per_liter', 8, 2)->nullable();
            $table->decimal('fuel_total', 8, 2)->nullable();
            $table->decimal('gross', 8, 2); // Será calculado automaticamente
            $table->decimal('net', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
