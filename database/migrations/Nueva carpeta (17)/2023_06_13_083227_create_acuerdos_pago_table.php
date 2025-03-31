<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcuerdosPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('acuerdos_pago', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->double('valor_deuda');
            $table->integer('total_cuotas');
            $table->double('valor_perdonar');
            $table->text('descripcion')->nullable();
            $table->string('estado',100);
            $table->string('tipo_descuento',100);
            $table->string('descuento',50);
            $table->bigInteger('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes');
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
        Schema::dropIfExists('acuerdos_pago');
    }
}
