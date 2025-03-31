<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesSuspensionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_suspensiones', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('tipo',50);
            $table->datetime('fecha_inicio');
            $table->bigInteger('cliente_id')->unsigned()->unique();
            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes');
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
        Schema::dropIfExists('clientes_suspensiones');
    }
}
