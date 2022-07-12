<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyContraintsToAmountConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amount_confirms', function (Blueprint $table) {
            $table->foreign('job_assign_id')->references('id')->on('job_assigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('staff_id')->references('id')->on('staff')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amount_confirms', function (Blueprint $table) {
            $table->dropForeign(['job_assign_id']);
            $table->dropForeign(['staff_id']);
        });
    }
}
