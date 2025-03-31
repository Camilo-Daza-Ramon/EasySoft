<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtencionClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atencion_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('identificacion');
            $table->bigInteger('identificacion_titular');
            $table->string('nombre')->nullable();
            $table->bigInteger('cliente_id')->unsigned()->nullable();
            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes');
            $table->integer('motivo_atencion_id')->unsigned()->nullable();
            $table->foreign('motivo_atencion_id')->references('id')->on('motivos_atencion');
            $table->text('descripcion')->nullable();
            $table->text('solucion')->nullable();
            $table->datetime('fecha_atencion_agente')->nullable();
            $table->string('estado');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('medio_atencion');
            $table->integer('municipio_id')->unsigned()->nullable();
            $table->foreign('municipio_id')->references('MunicipioId')->on('Municipios');
            $table->string('codigo',200)->nullable();
            $table->bigInteger('ticket_id')->unsigned()->nullable();
            $table->foreign('ticket_id')->references('TicketId')->on('ClientesTickets');
            $table->bigInteger('mantenimiento_id')->unsigned()->nullable();
            $table->foreign('mantenimiento_id')->references('MantId')->on('Mantenimientos');
            $table->bigInteger('pqr_id')->unsigned()->nullable();
            $table->foreign('pqr_id')->references('PqrId')->on('ClientesPQR');
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
        Schema::dropIfExists('atencion_clientes');
    }
}
