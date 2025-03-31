<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosMunicipiosMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_municipios_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meta_id')->unsigned();
            $table->foreign('meta_id')->references('id')->on('metas');
            $table->integer('proyecto_municipio_id')->unsigned();
            $table->foreign('proyecto_municipio_id')->references('id')->on('proyectos_municipios');
            $table->integer('total_accesos');
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
        Schema::dropIfExists('proyectos_municipios_metas');
    }
}
