<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetasClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas_clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('ClienteId')->unsigned()->unique();
            $table->foreign('ClienteId')->references('ClienteId')->on('Clientes');
            $table->integer('meta_id')->unsigned();
            $table->foreign('meta_id')->references('id')->on('metas');
            $table->string('idpunto')->unique();
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
        Schema::dropIfExists('metas_clientes');
    }
}
