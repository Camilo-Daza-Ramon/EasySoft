<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosTiposBeneficiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_tipos_beneficiarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->string('estado',20); //ACTIVO - INACTIVO
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
        Schema::dropIfExists('proyectos_tipos_beneficiarios');
    }
}
