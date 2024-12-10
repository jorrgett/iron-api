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
        Schema::create('tui_oem_depths', function (Blueprint $table) {
            $table->id();
            $table->string('tui_brand', 100);
            $table->string('tui_model', 100);
            $table->string('tui_size', 100);
            $table->float('otd', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tui_oem_depths');
    }
};
