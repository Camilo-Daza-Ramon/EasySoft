<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampanasCamposOpcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('campanas_campos_opciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('valor',200);         
            $table->string('estado',100);          
            $table->bigInteger('campo_id')->unsigned();
            $table->foreign('campo_id')->references('id')->on('campanas_campos');
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
        Schema::dropIfExists('campanas_campos_opciones');

    }
}
