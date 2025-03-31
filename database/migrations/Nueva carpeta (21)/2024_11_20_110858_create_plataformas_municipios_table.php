<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlataformasMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plataformas_municipios', function (Blueprint $table) {
            $table->integer('plataforma_id')->unsigned();
            $table->integer('municipio_id')->unsigned();

            $table->foreign('plataforma_id')->references('id')->on('plataformas_de_red')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('municipio_id')->references('MunicipioId')->on('Municipios')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['plataforma_id', 'municipio_id']);
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
        Schema::dropIfExists('plataformas_municipios');
    }
}
