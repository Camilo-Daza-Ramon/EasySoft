<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotivosAtencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivos_atencion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo');
            $table->string('categoria');
            $table->string('estado',12);
            $table->boolean('solicitud');
            $table->double('tiempo_limite');
            $table->string('unidad_medida',20);
            $table->boolean('condicional');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('motivos_atencion');
    }
}
