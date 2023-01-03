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
        Schema::table('batches', function (Blueprint $table) {
            $table->time('start_time')->nullable();
            $table->string('place')->nullable();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('place')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'place']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['place']);
        });
    }
};
