<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotasAcuerdoPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('cuotas_acuerdo_pago', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('cuota');
            $table->double('valor_pagar');
            $table->date('fecha_pago');
            $table->string('estado',100);
            $table->bigInteger('acuerdo_id')->unsigned();
            $table->foreign('acuerdo_id')->references('id')->on('acuerdos_pago');
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
        //
        Schema::dropIfExists('cuotas_acuerdo_pago');
    }
}
