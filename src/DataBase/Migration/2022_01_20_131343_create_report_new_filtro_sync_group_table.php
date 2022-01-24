<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportNewFiltroSyncGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_new_filtro_sync_group', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('filtro')->unsigned();
            $table->bigInteger('groupo_filtro')->unsigned();

            $table->foreign('filtro')->references('id')->on('report_new_filtro');
            $table->foreign('groupo_filtro')->references('id')->on('report_new_filtro_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_new_filtro_sync_group');
    }
}
