<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_comentarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('comentario');
            $table->integer('user_id')->unsigned();
            $table->bigInteger('solicitud_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('solicitudes_comentarios');
    }
}
