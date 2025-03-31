<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use phpDocumentor\Reflection\Types\Nullable;

class CreateCampanasObservacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('campanas_observaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('observacion')->Nullable();
            $table->bigInteger('campana_cliente_id')->unsigned();
            $table->foreign('campana_cliente_id')->references('id')->on('campanas_clientes');   
            $table->integer('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users');  
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
        Schema::dropIfExists('campanas_observaciones');

    }
}
