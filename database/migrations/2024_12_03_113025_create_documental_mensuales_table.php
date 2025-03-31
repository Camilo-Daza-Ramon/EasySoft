<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentalMensualesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documental_mensuales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('periodo');
            $table->bigInteger('documental_proyecto_id')->unsigned();
            $table->foreign('documental_proyecto_id')->references('id')->on('documental_proyectos');
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
        Schema::dropIfExists('documental_mensuales');
    }
}
