<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amount_confirms', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('job_assign_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedInteger('confirm_amount');
            $table->unsignedInteger('request_amount');
            $table->unsignedInteger('percentage_on');
            $table->string('quality')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('amount_confirms');
    }
}
