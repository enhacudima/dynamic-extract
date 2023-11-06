<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportNewApiExternalSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_new_api_external_schedule', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('method');
            $table->softDeletes();
            $table->bigInteger('report_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->time('time_start');
            $table->time('time_end');
            $table->integer('status')->default(1);

            $table->foreign('report_id')->references('id')->on('report_new_api_external_push_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repor_new_api_external_push_data');
    }
}
