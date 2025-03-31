<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMantenimientosPruebasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mantenimientos_pruebas', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->bigInteger('mantenimiento_id')->unsigned()->nullable();
            $table->foreign('mantenimiento_id')->references('MantId')->on('Mantenimientos')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('mantenimiento_preventivo_id')->unsigned()->nullable();
            $table->foreign('mantenimiento_preventivo_id')->references('ProgMantid')->on('MantenimientoProgramacion')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('prueba_id')->unsigned();
            $table->foreign('prueba_id')->references('TipoFallaId')->on('TB_TIPOS_FALLO');
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
        Schema::dropIfExists('mantenimientos_pruebas');
    }
}
