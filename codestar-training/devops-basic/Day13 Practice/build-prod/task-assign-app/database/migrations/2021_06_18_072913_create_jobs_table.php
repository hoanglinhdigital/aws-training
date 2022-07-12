<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('code')->nullable()->unique();
            $table->string('name');
            $table->unsignedBigInteger('assigner_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('job_type_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->date('deadline');
            $table->unsignedInteger('period')->nullable();
            $table->string('period_unit')->default('Ngày');
            $table->unsignedInteger('lsx_amount')->nullable();
            $table->unsignedInteger('assign_amount')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('Chưa nhận');
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
        Schema::dropIfExists('jobs');
    }
}
