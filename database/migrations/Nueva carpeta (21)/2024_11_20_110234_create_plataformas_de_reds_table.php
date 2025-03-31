<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlataformasDeRedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plataformas_de_red', function (Blueprint $table) {
            $table->increments('id');
            $table->string("nombre", 200);
            $table->string("link", 200);
            $table->integer('instruccion_id')->unsigned()->nullable();
            $table->foreign('instruccion_id')->references('id')->on('plataforma_red_instrucciones');
            $table->integer('dato_acceso_id')->unsigned()->nullable();
            $table->foreign('dato_acceso_id')->references('id')->on('plataforma_red_accesos');
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
        Schema::dropIfExists('plataformas_de_red');
    }
}
