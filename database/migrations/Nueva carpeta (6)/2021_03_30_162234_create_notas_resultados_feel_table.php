<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasResultadosFeelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_resultados_feel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('factura_nota_id')->unsigned();
            $table->foreign('factura_nota_id')->references('id')->on('facturas_notas');            
            $table->datetime('fecha');
            $table->string('concepto',100);
            $table->text('detalles')->nullable();
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
        Schema::dropIfExists('notas_resultados_feel');
    }
}
