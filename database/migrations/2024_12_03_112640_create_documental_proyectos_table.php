<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentalProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documental_proyectos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre',500);
            $table->string('tipo',20);
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
        Schema::dropIfExists('documental_proyectos');
    }
}
