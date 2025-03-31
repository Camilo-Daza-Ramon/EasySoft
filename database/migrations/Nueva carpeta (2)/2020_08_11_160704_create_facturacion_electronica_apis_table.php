<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturacionElectronicaApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturacion_electronica_apis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url_api');
            $table->string('token_identificador');
            $table->string('controlador');
            $table->string('accion');
            $table->integer('proyecto_id')->unsigned()->unique();
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
        Schema::dropIfExists('facturacion_electronica_apis');
    }
}
