<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->double('cantidad');
            $table->string('unidad_medida', 20);
            $table->double('valor');
            $table->boolean('iva');
            $table->string('estado', 12);
            $table->string('tipo_servicio');
            $table->integer('municipio_id')->nullable()->unsigned();
            $table->foreign('municipio_id')->references('MunicipioId')->on('Municipios');
            $table->string('estrato', 3)->nullable();
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
        Schema::dropIfExists('servicios');
    }
}
