<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentalArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documental_archivos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',200);
            $table->string('ruta', 200);
            $table->string('tipo',5);
            $table->bigInteger('documental_version_id')->unsigned();
            $table->foreign('documental_version_id')->references('id')->on('documental_versiones');
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
        Schema::dropIfExists('documental_archivos');
    }
}
