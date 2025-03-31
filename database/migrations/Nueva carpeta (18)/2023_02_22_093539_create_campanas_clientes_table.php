<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampanasClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('campanas_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('estado',100);
            $table->bigInteger('campana_id')->unsigned();
            $table->foreign('campana_id')->references('id')->on('campanas');   
            $table->bigInteger('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes'); 
            $table->bigInteger('ticket_id')->unsigned()->nullable();
            $table->foreign('ticket_id')->references('TicketId')->on('ClientesTickets');
            $table->bigInteger('mantenimiento_id')->unsigned()->nullable();
            $table->foreign('mantenimiento_id')->references('MantId')->on('Mantenimientos');
            $table->bigInteger('pqr_id')->unsigned()->nullable();
            $table->foreign('pqr_id')->references('PqrId')->on('ClientesPQR');  
            $table->datetime('fecha_hora_rellamar');
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
        Schema::dropIfExists('campanas_clientes');

    }
}
