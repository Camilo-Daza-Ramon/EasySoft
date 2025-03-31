<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetasMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas_municipios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meta_id')->unsigned();
            $table->foreign('meta_id')->references('id')->on('metas');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('MunicipioId')->on('Municipios');
            $table->integer('total_accesos');
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
        Schema::dropIfExists('metas_municipios');
    }
}
