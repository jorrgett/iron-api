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
        Schema::create('service_balancing', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sequence_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('odoo_id')->unique();
            $table->string('location');
            $table->float('lead_used')->nullable();
            $table->string('type_lead')->nullable();
            $table->boolean('balanced')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_balancing');
    }
};
