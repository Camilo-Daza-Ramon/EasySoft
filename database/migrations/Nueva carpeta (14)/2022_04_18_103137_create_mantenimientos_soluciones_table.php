<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMantenimientosSolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mantenimientos_soluciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mantenimiento_id')->unsigned();
            $table->foreign('mantenimiento_id')->references('MantId')->on('Mantenimientos')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('solucion_id')->unsigned();
            $table->foreign('solucion_id')->references('TipoFallaId')->on('TB_TIPOS_FALLO');
            $table->text('descripcion');
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
        Schema::dropIfExists('mantenimientos_soluciones');
    }
}
