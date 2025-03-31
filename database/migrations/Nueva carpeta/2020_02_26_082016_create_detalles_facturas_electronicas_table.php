<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesFacturasElectronicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_facturas_electronicas', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('factura_electronica_id')->unsigned();
            $table->foreign('factura_electronica_id')->references('id')->on('facturas_electronicas');
            $table->timestamp('fecha');
            $table->string('concepto', 100);
            $table->text('detalles')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_facturas_electronicas');
    }
}
