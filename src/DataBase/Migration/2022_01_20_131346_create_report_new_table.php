<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_new', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('comments');
            $table->bigInteger('table_name')->unsigned();
            $table->bigInteger('filtro')->nullable();
            $table->string('can');
            $table->integer('status')->default(1);

            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('report_new');
    }
}
