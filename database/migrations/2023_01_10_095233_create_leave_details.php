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
        Schema::create('leave_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('leave_type_id');
            $table->string('leave_cause');
            $table->string('per_date_type');
            $table->integer('per_leave_days')->nullable();
            $table->string('temp_date_type');
            $table->integer('temp_leave_days')->nullable();
            $table->string('leave_status');
            $table->json('validation_json')->nullable();
            $table->json('validation')->nullable();
            $table->json('attachment_json')->nullable();
            $table->json('attachment')->nullable();
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
        Schema::dropIfExists('leave_details');
    }
};
