<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_proyectos', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('proyecto_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('proyecto_id')->references('ProyectoID')->on('Proyectos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'proyecto_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_proyectos');
    }
}
