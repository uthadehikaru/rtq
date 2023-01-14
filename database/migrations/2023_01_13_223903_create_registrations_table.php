<?php

use App\Models\User;
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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('registration_no');
            $table->string('full_name');
            $table->string('short_name');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['male','female']);
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('type');
            $table->string('reference')->nullable();
            $table->enum('schedule_option', ['weekend','weekday'])->nullable();
            $table->string('activity')->nullable();
            $table->time('school_start_time')->nullable();
            $table->time('school_end_time')->nullable();
            $table->string('school_level')->nullable();
            $table->string('class')->nullable();
            $table->string('school_name')->nullable();
            $table->string('start_school')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('reference_schedule')->nullable();
            $table->foreignIdFor(User::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrations');
    }
};
