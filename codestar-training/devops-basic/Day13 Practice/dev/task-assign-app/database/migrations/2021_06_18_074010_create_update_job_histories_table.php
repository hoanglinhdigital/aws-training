<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateJobHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_job_histories', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('job_id');
            $table->dateTime('date');
            $table->string('field');
            $table->string('old_value');
            $table->string('new_value');
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
        Schema::dropIfExists('update_job_histories');
    }
}
