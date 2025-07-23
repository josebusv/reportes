<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // Relación con clients
            $table->string('brand'); // Marca
            $table->string('model'); // Modelo
            $table->string('inventory_number')->unique(); // Nº. Inventario
            $table->string('location')->nullable(); // Ubicación
            $table->text('accessories')->nullable(); // Accesorios
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
};
