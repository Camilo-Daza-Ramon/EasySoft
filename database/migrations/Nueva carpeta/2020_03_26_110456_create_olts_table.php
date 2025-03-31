<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOltsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('olts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('ip');
            $table->string('usuario');
            $table->string('password');
            $table->string('latitud');
            $table->string('longitud');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('MunicipioId')->on('Municipios');
            $table->string('estado', 12);
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
        Schema::dropIfExists('olts');
    }
}
