<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosPreguntasRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_preguntas_respuestas', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('cliente_id')->unsigned();
            $table->integer('proyecto_id')->unsigned();
            $table->bigInteger('proyecto_pregunta_id')->unsigned();
            $table->text('respuesta');

            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes');
            $table->foreign('proyecto_id')->references('ProyectoID')->on('Proyectos');
            $table->foreign('proyecto_pregunta_id')->references('id')->on('proyectos_preguntas');

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
        Schema::dropIfExists('proyectos_preguntas_respuestas');
    }
}
