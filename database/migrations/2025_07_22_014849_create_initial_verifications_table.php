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
        Schema::create('initial_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->unique()->constrained('reports')->onDelete('cascade'); // Relación con reports (unique)
            $table->boolean('normal')->default(false);
            $table->boolean('irregular')->default(false);
            $table->boolean('out_of_service')->default(false);
            $table->boolean('requested_by_client')->default(false);
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
        Schema::dropIfExists('initial_verifications');
    }
};
