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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique(); // Nº. (057, 070, etc.)
            $table->date('report_date'); // FECHA
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade'); // Relación con equipment
            $table->enum('report_type', ['endoscopy', 'electronic']); // Para diferenciar entre tipos de reportes
            $table->text('reported_failure')->nullable(); // FALLA REPORTADA
            $table->text('service_performed')->nullable(); // SERVICIO REALIZADO
            $table->decimal('visit_value', 10, 2)->nullable(); // Valor Visita
            $table->decimal('iva_value', 10, 2)->nullable(); // + I.V.A
            $table->string('billing_to')->nullable(); // Facturar a
            $table->foreignId('engineer_id')->nullable()->constrained('users')->onDelete('set null'); // Relación con users (Ingeniero)
            $table->string('service_provider_name')->nullable(); // Prestador del Servicio
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
        Schema::dropIfExists('reports');
    }
};
