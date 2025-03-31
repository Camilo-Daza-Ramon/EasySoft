<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesRestriccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_restricciones', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('cliente_id')->unsigned()->unique();
            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes');
            $table->text('observaciones')->Nullable();
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
        Schema::dropIfExists('clientes_restricciones');
    }
}
