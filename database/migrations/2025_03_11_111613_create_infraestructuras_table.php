<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfraestructurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infraestructuras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("nombre",200);
            $table->string("latitud",100);
            $table->string("longitud",100);
            $table->integer("municipio_id")->unsigned();
            $table->string("categoria",100);
            $table->string("tipo_categoria",100)->nullable();
            $table->string("direccion",200);
            $table->text("datos_ubicacion")->nullable();
            $table->text("descripcion")->nullable();
            $table->unsignedBigInteger("infraestructura_id")->nullable();
            $table->integer("proveedor_id")->unsigned()->nullable();
            $table->string("estado",20);

            $table->foreign("municipio_id")->references('MunicipioId')->on('Municipios');
            $table->foreign("infraestructura_id")->references('id')->on('infraestructuras');
            $table->foreign("proveedor_id")->references('id')->on('proveedores');

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
        Schema::dropIfExists('infraestructuras');
    }
}
