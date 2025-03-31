<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesOntsOltsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_onts_olts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('ClienteId')->unsigned();
            $table->foreign('ClienteId')->references('ClienteId')->on('Clientes');

            $table->bigInteger('ActivoFijoId')->unsigned();
            $table->foreign('ActivoFijoId')->references('ActivoFijoId')->on('ActivosFijos');
            
            $table->integer('olt_id')->unsigned();
            $table->foreign('olt_id')->references('id')->on('olts');

            $table->integer('contrato_id')->unsigned();
            $table->foreign('contrato_id')->references('id')->on('clientes_contratos');            

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('clientes_onts_olts');
    }
}
