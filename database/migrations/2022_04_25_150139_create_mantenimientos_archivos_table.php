<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMantenimientosArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mantenimientos_archivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre',250);
            $table->string('archivo',250)->nullable();
            $table->string('tipo_archivo',5);
            $table->bigInteger('mantenimiento_id')->unsigned()->nullable();
            $table->foreign('mantenimiento_id')->references('MantId')->on('Mantenimientos');

            $table->integer('mantenimiento_preventivo_id')->unsigned()->nullable();
            $table->foreign('mantenimiento_preventivo_id')->references('ProgMantid')->on('MantenimientoProgramacion')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('mantenimientos_archivos');
    }
}
