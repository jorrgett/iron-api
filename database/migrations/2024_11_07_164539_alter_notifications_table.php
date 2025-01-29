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
        Schema::table('notifications', function(Blueprint $table) {
            $table->string('topic_1')->nullable();
            $table->string('topic_2')->nullable();
            $table->boolean('promotion')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('topic_1');
            $table->dropColumn('topic_2');
            $table->dropColumn('promotion');
        });
    }
};
