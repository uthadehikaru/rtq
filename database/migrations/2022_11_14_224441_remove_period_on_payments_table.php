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
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['period_id']);
            $table->dropColumn(['period_id']);
        });

        Schema::table('payment_details', function (Blueprint $table) {
            $table->foreignId('period_id')->constrained();
        });
    }
};
