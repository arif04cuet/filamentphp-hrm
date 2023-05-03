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
        Schema::create('employees', function (Blueprint $table) {
            $table->bigInteger('id')->primary()->unique();
            $table->string('name_en')->nullable();
            $table->string('name_bn')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('tel_office')->nullable();
            $table->string('tel_home')->nullable();
            $table->string('mobile_office')->nullable();
            $table->string('mobile_home')->nullable();
            $table->string('photo')->nullable();
            $table->string('signature')->nullable();
            $table->unsignedBigInteger('doptor_id')->nullable();
            $table->string('gov_id')->nullable();
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
        Schema::dropIfExists('employees');
    }
};