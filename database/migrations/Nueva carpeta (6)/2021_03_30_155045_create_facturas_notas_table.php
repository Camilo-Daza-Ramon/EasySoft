<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_notas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_nota',7);
            $table->integer('tipo_concepto_id')->unsigned();
            $table->foreign('tipo_concepto_id')->references('id')->on('conceptos_facturacion_electronica');
            $table->integer('tipo_operacion_id')->unsigned();
            $table->foreign('tipo_operacion_id')->references('id')->on('conceptos_facturacion_electronica');
            $table->integer('tipo_negociacion_id')->unsigned();
            $table->foreign('tipo_negociacion_id')->references('id')->on('conceptos_facturacion_electronica');
            $table->integer('tipo_medio_pago_id')->unsigned();
            $table->foreign('tipo_medio_pago_id')->references('id')->on('conceptos_facturacion_electronica');
            $table->datetime('fecha_expedision');
            $table->bigInteger('factura_id')->unsigned();
            $table->foreign('factura_id')->references('FacturaId')->on('Facturacion');
            $table->boolean('reportada')->nullable();
            $table->string('numero_nota_dian')->nullable();
            $table->bigInteger('documento_id_feel')->nullable();
            $table->text('archivo')->nullable();
            $table->double('valor_total');
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
        Schema::dropIfExists('facturas_notas');
    }
}
