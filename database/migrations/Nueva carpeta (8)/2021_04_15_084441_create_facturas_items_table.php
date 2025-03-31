<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('concepto');
            $table->double('cantidad');
            $table->double('valor_unidad');
            $table->double('iva');
            $table->double('valor_iva');
            $table->double('valor_total');
            $table->bigInteger('factura_id')->unsigned();
            $table->foreign('factura_id')->references('FacturaId')->on('Facturacion');
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
        Schema::dropIfExists('facturas_items');
    }
}
