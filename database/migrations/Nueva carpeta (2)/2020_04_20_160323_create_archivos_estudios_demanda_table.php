<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivosEstudiosDemandaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos_estudios_demanda', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('archivo');
            $table->string('tipo');
            $table->integer('estudio_demanda_id')->unsigned();
            $table->foreign('estudio_demanda_id')->references('id')->on('estudios_demanda');
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
        Schema::dropIfExists('archivos_estudios_demanda');
    }
}
