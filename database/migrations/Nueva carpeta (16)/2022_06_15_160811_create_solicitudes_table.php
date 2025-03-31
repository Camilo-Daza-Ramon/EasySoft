<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('atencion_cliente_id')->unsigned();
            $table->foreign('atencion_cliente_id')->references('id')->on('atencion_clientes'); 
            $table->string('estado',10);
            $table->datetime('fecha_hora_solicitud');
            $table->date('fecha_limite');
            $table->datetime('fecha_hora_atendida')->nullable();
            $table->string('celular',30);
            $table->string('jornada',7);
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
        Schema::dropIfExists('solicitudes');
    }
}
