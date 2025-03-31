<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasElectronicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_electronicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('reportada');
            $table->string('numero_factura_dian', 100)->nullable();
            $table->bigInteger('FacturaId')->unique()->unsigned();
            $table->foreign('FacturaId')->references('FacturaId')->on('Facturacion');
            $table->bigInteger('documento_id_feel')->nullable();
            $table->text('archivo');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas_electronicas');
    }
}
