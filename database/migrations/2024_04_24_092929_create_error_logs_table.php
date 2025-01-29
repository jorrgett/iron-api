<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sequence_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('date');
            $table->string('screen', 200)->nullable();
            $table->string('action', 200)->nullable();
            $table->string('api', 200);
            $table->string('error_message', 1024);
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('error_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
