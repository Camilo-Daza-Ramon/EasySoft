<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuspensionesTemporalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspensiones_temporales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('descripcion');
            $table->bigInteger('cliente_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->datetime('fecha_hora_inicio');
            $table->datetime('fecha_hora_fin');
            $table->date('fecha_solicitud');
            $table->string('estado', 20);
            $table->bigInteger('novedad_id')->unsigned()->nullable();
            $table->bigInteger('solicitud_id')->unsigned()->nullable();
            
            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('novedad_id')->references('id')->on('novedades');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes');
           
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
        Schema::dropIfExists('suspensiones_temporales');
    }
}
