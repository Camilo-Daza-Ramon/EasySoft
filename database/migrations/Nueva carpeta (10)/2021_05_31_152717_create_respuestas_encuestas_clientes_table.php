<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestasEncuestasClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_encuestas_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('pregunta');
            $table->text('respuesta');
            $table->bigInteger('atencion_cliente_id')->unsigned();
            $table->foreign('atencion_cliente_id')->references('id')->on('atencion_clientes');
            $table->integer('encuesta_satisfaccion_id')->unsigned();
            $table->foreign('encuesta_satisfaccion_id')->references('id')->on('encuestas_satisfaccion');
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
        Schema::dropIfExists('respuestas_encuestas_clientes');
    }
}
