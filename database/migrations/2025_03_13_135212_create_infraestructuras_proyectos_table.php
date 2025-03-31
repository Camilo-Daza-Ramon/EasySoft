<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfraestructurasProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infraestructuras_proyectos', function (Blueprint $table) {
            $table->unsignedBigInteger("nodo_id");
            $table->unsignedInteger("proyecto_id");

            $table->foreign("nodo_id")->references('id')->on('infraestructuras');
            $table->foreign("proyecto_id")->references('ProyectoID')->on('Proyectos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infraestructuras_proyectos');
    }
}
