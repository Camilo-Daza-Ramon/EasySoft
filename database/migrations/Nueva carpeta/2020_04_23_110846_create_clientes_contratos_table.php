<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_contratos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('referencia', 50)->nullable();
            $table->string('tipo_cobro', 12);
            $table->integer('vigencia_meses');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->boolean('clausula_permanencia');
            $table->string('estado', 20);
            $table->integer('vendedor_id')->unsigned();
            $table->foreign('vendedor_id')->references('id')->on('users');
            $table->bigInteger('ClienteId')->unsigned();
            $table->foreign('ClienteId')->references('ClienteId')->on('Clientes');
            $table->string('archivo')->nullable();
            $table->date('fecha_instalacion')->nullable();
            $table->text('observacion')->nullable();
            $table->date('fecha_operacion')->nullable();
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
        Schema::dropIfExists('clientes_contratos');
    }
}
