<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string("nombre",200);
            $table->string("tipo_identificacion",20);
            $table->bigInteger("identificacion");
            $table->string("tipo",100);
            $table->string("direccion",200);
            $table->integer("municipio_id")->unsigned();
            $table->string("estado",12);
            $table->string("telefono",200)->nullable();
            $table->string("celular",200)->nullable();
            $table->string("correo_electronico",200);
            $table->timestamps();

            $table->foreign("municipio_id")->references('MunicipioId')->on('Municipios');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
