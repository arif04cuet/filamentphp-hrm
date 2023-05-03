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
        Schema::create('attendance_lists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->longText('ip');
            $table->date('date');
            $table->time('entry_time');
            $table->string('status');
            $table->boolean('late_flag')->default(0);
            $table->string('late_cause')->nullable();
            $table->date('approved_at')->nullable();
            $table->time('leave_time')->nullable();
            $table->bigInteger('doptor_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_lists');
    }
};
