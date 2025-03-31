<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntosAtencionAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntos_atencion_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('punto_atencion_id')->unsigned();
            $table->foreign('punto_atencion_id')->references('id')->on('puntos_atencion');
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
        Schema::dropIfExists('puntos_atencion_areas');
    }
}
