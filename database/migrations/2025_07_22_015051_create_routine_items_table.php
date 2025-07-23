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
        Schema::create('routine_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la rutina
            $table->enum('type', ['endoscopy', 'electronic']); // Tipo de formulario al que pertenece
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
        Schema::dropIfExists('routine_items');
    }
};
