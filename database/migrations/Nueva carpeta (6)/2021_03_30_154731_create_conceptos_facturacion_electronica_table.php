<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptosFacturacionElectronicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceptos_facturacion_electronica', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('codigo',10);
            $table->string('tipo',255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conceptos_facturacion_electronica');
    }
}
