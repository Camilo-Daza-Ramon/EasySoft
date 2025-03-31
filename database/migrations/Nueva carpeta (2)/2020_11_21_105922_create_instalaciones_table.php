<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstalacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instalaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('ClienteId')->unsigned();
            $table->foreign('ClienteId')->references('ClienteId')->on('Clientes');

            $table->string('serial_ont');
            $table->string('tipo_conexion');
            $table->string('marca_equipo')->nullable();
            $table->string('serial_equipo')->nullable();
            $table->string('estado_equipo');

            $table->integer('cantidad_equipos_conectados');
            $table->string('tipo_conexion_electrica');

            $table->string('tipo_proteccion_electrica');
            $table->string('marca_proteccion_electrica')->nullable();
            $table->string('serial_proteccion_electrica')->nullable();
            $table->string('estado_conexion_electrica');

            $table->double('velocidad_bajada');
            $table->double('velocidad_subida');            
            
            $table->integer('conector');
            $table->integer('pigtail');

            $table->integer('cant_retenciones');
            $table->string('tipo_retenciones');

            $table->double('cinta_bandit');
            $table->integer('hebilla');

            $table->integer('gancho_poste')->nullable();
            $table->integer('gancho_pared')->nullable();

            $table->integer('cant_correa_amarre');
            $table->string('tipo_correa_amarre');

            $table->integer('cant_chazo');
            $table->string('tipo_chazo');

            $table->integer('tornillo');
            $table->integer('roseta');
            $table->integer('patch_cord_fibra');
            $table->integer('patch_cord_utp');

            $table->double('fibra_drop_desde');
            $table->double('fibra_drop_hasta');

            $table->integer('caja');
            $table->integer('puerto');
            $table->integer('sp_splitter');
            $table->integer('ss_splitter');
            $table->integer('tarjeta');
            $table->integer('modulo');

            $table->string('servicio_activo');
            $table->string('cumple_velocidad_contratada');

            $table->string('latitud');
            $table->string('longitud');
            $table->text('observaciones');
            $table->date('fecha');

            $table->string('estado');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('auditor_id')->unsigned()->nullable();
            $table->foreign('auditor_id')->references('id')->on('users');

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
        Schema::dropIfExists('instalaciones');
    }
}
