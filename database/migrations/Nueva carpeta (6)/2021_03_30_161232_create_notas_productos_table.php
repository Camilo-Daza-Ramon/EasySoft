<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('concepto');
            $table->double('cantidad');
            $table->double('valor_unidad');
            $table->double('iva');
            $table->double('valor_iva');
            $table->double('valor_total');
            $table->bigInteger('factura_nota_id')->unsigned();
            $table->foreign('factura_nota_id')->references('id')->on('facturas_notas');
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
        Schema::dropIfExists('notas_productos');
    }
}
