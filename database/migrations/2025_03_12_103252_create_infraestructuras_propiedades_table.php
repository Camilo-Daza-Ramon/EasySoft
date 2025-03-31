<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfraestructurasPropiedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infraestructuras_propiedades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("infraestructura_id");
            $table->string("nombre",200);
            $table->string("valor",100);
            $table->string("unidad_medida",100);

            $table->foreign("infraestructura_id")->references('id')->on('infraestructuras');
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
        Schema::dropIfExists('infraestructuras_propiedades');
    }
}
