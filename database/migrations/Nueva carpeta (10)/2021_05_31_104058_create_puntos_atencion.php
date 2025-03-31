<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntosAtencion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntos_atencion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('direccion');
            $table->string('barrio');
            $table->string('latitud');
            $table->string('longitud');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('MunicipioId')->on('Municipios');
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
        Schema::dropIfExists('puntos_atencion');
    }
}
