<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentalVersionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documental_versiones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo',200);
            $table->decimal('version', 10,1);
            $table->string('estado', 20);
            $table->bigInteger('documental_proyecto_id')->unsigned();
            $table->foreign('documental_proyecto_id')->references('id')->on('documental_proyectos');
            $table->bigInteger('documental_mensual_id')->unsigned()->nullable();
            $table->foreign('documental_mensual_id')->references('id')->on('documental_mensuales');            
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
        Schema::dropIfExists('documental_versiones');
    }
}
