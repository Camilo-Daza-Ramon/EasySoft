<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoreosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoreos_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fsp')->nullable();
            $table->string('ont_id')->nullable();
            $table->string('control_flag')->nullable();
            $table->string('run_state')->nullable();
            $table->string('config_state')->nullable();
            $table->string('match_state')->nullable();
            $table->string('last_down_cause')->nullable();
            $table->string('last_up_time')->nullable();
            $table->string('last_down_time')->nullable();
            $table->string('last_dying_gasp_time')->nullable();
            $table->bigInteger('cliente_id')->unsigned()->unique();
            $table->foreign('cliente_id')->references('ClienteId')->on('Clientes');
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
        Schema::dropIfExists('monitoreos_clientes');
    }
}
