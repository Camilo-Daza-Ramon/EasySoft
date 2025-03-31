<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampanasCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('campanas_campos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre',200);
            $table->string('tipo',200);
            $table->boolean('estado');
            $table->boolean('obligatorio');
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
        Schema::dropIfExists('campanas_campos');

    }
}
