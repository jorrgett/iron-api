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
        Schema::create('service_alignment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->unsignedBigInteger('service_id');
            $table->string('eje', 10);
            $table->string('valor', 15);
            $table->string('full_convergence_d', 10);
            $table->string('semiconvergence_izq_d', 10);
            $table->string('semiconvergence_der_d', 10);
            $table->string('camber_izq_d', 10);
            $table->string('camber_der_d', 10);
            $table->unsignedInteger('sequence_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_alignment');
    }
};
