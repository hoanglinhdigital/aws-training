<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_assigns', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('process_method_id');
            $table->unsignedBigInteger('parent_id');
            $table->boolean('direct_report')->nullable();
            $table->text('sms')->nullable();
            $table->string('status');
            $table->text('deny_reason')->nullable();
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
        Schema::dropIfExists('job_assigns');
    }
}
