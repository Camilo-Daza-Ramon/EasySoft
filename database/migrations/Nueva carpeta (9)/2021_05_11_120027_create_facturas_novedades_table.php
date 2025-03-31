<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_novedades', function (Blueprint $table) {
            $table->bigInteger('factura_id')->unsigned();
            $table->foreign('factura_id')->references('FacturaId')->on('Facturacion')->onDelete('cascade');
            $table->bigInteger('novedad_id')->unsigned();
            $table->foreign('novedad_id')->references('id')->on('novedades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas_novedades');
    }
}
