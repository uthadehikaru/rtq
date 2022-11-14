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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['teacher_id']);
        });

        Schema::table('presents', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['teacher_id']);
            $table->dropForeign(['member_id']);
            $table->dropColumn(['member_id']);

            $table->foreignId('user_id')->constrained();
            $table->enum('type',['teacher','member']);
        });
    }
};
