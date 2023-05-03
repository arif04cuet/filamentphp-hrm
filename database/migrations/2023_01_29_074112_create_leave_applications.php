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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->bigInteger('doptor_id');
            $table->bigInteger('leave_type_id');
            $table->bigInteger('leave_cause_id');
            $table->date('leave_from');
            $table->date('leave_to');
            $table->integer('total_leave_days');
            $table->string('address_during_leave')->nullable();
            $table->json('attachment')->nullable();
            $table->string('status')->default('Pending');
            $table->bigInteger('apply_to')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_applications');
    }
};
