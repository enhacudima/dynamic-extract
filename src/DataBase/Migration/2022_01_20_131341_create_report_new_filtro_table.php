<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportNewFiltroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_new_filtro', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('value');
            $table->string('name');
            $table->string('type');
            $table->integer('status')->default(1);

            $table->bigInteger('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_new_filtro');
    }
}
