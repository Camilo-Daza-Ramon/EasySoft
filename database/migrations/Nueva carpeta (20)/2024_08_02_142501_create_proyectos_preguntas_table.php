<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_preguntas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('pregunta');
            $table->string('tipo', 200);
            $table->text('opciones_respuesta')->nullable();
            $table->boolean('obligatoriedad');
            $table->string('estado', 12);
            $table->integer('proyecto_id')->unsigned();
            $table->foreign('proyecto_id')->references('ProyectoID')->on('Proyectos');
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
        Schema::dropIfExists('proyectos_preguntas');
    }
}
