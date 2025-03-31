<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanesMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes_municipios', function (Blueprint $table) {
            $table->integer('proyecto_municipio_id')->unsigned();
            $table->integer('plan_comercial_id')->unsigned();


            $table->foreign('proyecto_municipio_id')->references('id')->on('proyectos_municipios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('plan_comercial_id')->references('PlanId')->on('PlanesComerciales')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['proyecto_municipio_id', 'plan_comercial_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planes_municipios');
    }
}
