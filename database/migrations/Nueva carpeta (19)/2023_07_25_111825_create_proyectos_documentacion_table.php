<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosDocumentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_documentacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 200); //es el nombre que se asignan a los name de los input
            $table->string('alias', 200);  //es el nombre con el que se mostrará en los formularios
            $table->text('descripcion')->nullable();
            $table->string('tipo', 12);//OBLIGATORIO - OPCIONAL
            $table->string('estado',20); //ACTIVO - INACTIVO
            $table->integer('proyecto_id')->unsigned();
            $table->boolean('coordenadas');
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
        Schema::dropIfExists('proyectos_documentacion');
    }
}
