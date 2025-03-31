<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfraestructurasEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infraestructuras_equipos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inventario_id');
            $table->string('ip_gestion',100)->nullable();
            $table->string('usuario',100)->nullable();
            $table->text('password')->nullable();
            $table->unsignedBigInteger('infraestructura_id');

            $table->foreign("infraestructura_id")->references('id')->on('infraestructuras');
            $table->foreign("inventario_id")->references('ActivoFijoId')->on('ActivosFijos');
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
        Schema::dropIfExists('infraestructuras_equipos');
    }
}
