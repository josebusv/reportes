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
        Schema::create('report_checklist_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->onDelete('cascade');
            $table->enum('status', ['good', 'regular', 'bad', 'not_applicable']); // B, R, M, N.A
            $table->integer('value_1')->nullable(); // Columna '1' (para endoscopia)
            $table->integer('value_2')->nullable(); // Columna '2'
            $table->integer('value_3')->nullable(); // Columna '3'
            $table->integer('value_4')->nullable(); // Columna '4'
            $table->boolean('angulation_up')->default(false); // Para el ítem 'Angulación' de endoscopia
            $table->boolean('angulation_down')->default(false);
            $table->boolean('angulation_left')->default(false);
            $table->boolean('angulation_right')->default(false);
            $table->timestamps();

            $table->unique(['report_id', 'checklist_item_id'], 'report_checklist_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_checklist_status');
    }
};
