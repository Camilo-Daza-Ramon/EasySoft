<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCamposVisualizarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('campos_visualizar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('campo');
            $table->bigInteger('campana_id')->unsigned();
            $table->foreign('campana_id')->references('id')->on('campanas');   
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
        //
        Schema::dropIfExists('campos_visualizar');

    }
}
