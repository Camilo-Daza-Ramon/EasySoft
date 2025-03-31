<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstalacionesArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instalaciones_archivos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',250);
            $table->string('archivo',250)->nullable();
            $table->string('tipo_archivo',5);
            $table->string('estado', 12);
            $table->integer('instalacion_id')->unsigned();
            $table->foreign('instalacion_id')->references('id')->on('instalaciones');
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
        Schema::dropIfExists('instalaciones_archivos');
    }
}
