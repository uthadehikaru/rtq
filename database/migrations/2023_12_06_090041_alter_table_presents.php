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
        Schema::table('presents', function (Blueprint $table) {
            $table->string('photo_out')->nullable();
            $table->timestamp('leave_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presents', function (Blueprint $table) {
            $table->dropColumn(['photo_out','leave_at']);
        });
    }
};
