<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos_clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',250);
            $table->string('archivo',250)->nullable();
            $table->string('tipo_archivo',5);
            $table->string('estado', 12);
            $table->bigInteger('ClienteId')->unsigned();
            $table->foreign('ClienteId')->references('ClienteId')->on('Clientes');
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
        Schema::dropIfExists('archivos_clientes');
    }
}
