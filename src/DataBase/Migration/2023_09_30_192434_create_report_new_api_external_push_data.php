<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportNewApiExternalPushData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_new_api_external_push_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->softDeletes();
            $table->text('access_link');
            $table->bigInteger('table_name')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->dateTime('expire_at')->nullable();
            $table->integer('advance_query')->nullable();
            $table->integer('paginate')->nullable();
            $table->text('text_query')->nullable();
            $table->integer('status')->default(1);

            $table->foreign('table_name')->references('id')->on('report_new_tables');
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
