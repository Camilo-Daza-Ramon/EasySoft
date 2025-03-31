<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntosAtencionVentanillas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntos_atencion_ventanillas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('punto_atencion_area_id')->unsigned();
            $table->foreign('punto_atencion_area_id')->references('id')->on('puntos_atencion_areas');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('puntos_atencion_ventanillas');
    }
}
