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
        Schema::table('tui_oem_depths', function (Blueprint $table) {
            $table->unsignedBigInteger('sequence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tui_oem_depths', function (Blueprint $table) {
            $table->dropColumn('sequence_id');
        });
    }
};
