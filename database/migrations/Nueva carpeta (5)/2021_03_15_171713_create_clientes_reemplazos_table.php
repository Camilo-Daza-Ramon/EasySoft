<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesReemplazosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_reemplazos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meta_cliente_id')->unsigned();
            $table->foreign('meta_cliente_id')->references('id')->on('metas_clientes');

            $table->bigInteger('antiguo_cliente_contrato_id')->unsigned();
            $table->foreign('antiguo_cliente_contrato_id')->references('id')->on('clientes_contratos');

            $table->bigInteger('cliente_nuevo_id')->unsigned()->unique();
            $table->foreign('cliente_nuevo_id')->references('ClienteId')->on('Clientes');

            $table->bigInteger('nuevo_cliente_contrato_id')->unsigned();
            $table->foreign('nuevo_cliente_contrato_id')->references('id')->on('clientes_contratos');

            $table->date('fecha_reemplazo');
            $table->text('observacion')->nullable();

            $table->integer('cliente_reemplazo_id')->nullable()->unsigned()->unique();
            $table->foreign('cliente_reemplazo_id')->references('id')->on('clientes_reemplazos');
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
        Schema::dropIfExists('clientes_reemplazos');
    }
}
