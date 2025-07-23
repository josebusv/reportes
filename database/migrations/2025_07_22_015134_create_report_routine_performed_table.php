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
        Schema::create('report_routine_performed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('routine_item_id')->constrained('routine_items')->onDelete('cascade');
            $table->boolean('performed')->default(false); // Si la rutina fue realizada
            $table->timestamps();

            $table->unique(['report_id', 'routine_item_id'], 'report_routine_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_routine_performed');
    }
};
