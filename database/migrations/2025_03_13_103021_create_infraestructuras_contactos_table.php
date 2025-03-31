<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfraestructurasContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infraestructuras_contactos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 200);
            $table->string('celular', 10);
            $table->string('telefono', 10);
            $table->string('cargo_presentativo', 100);
            $table->unsignedBigInteger("infraestructura_id");

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
        Schema::dropIfExists('infraestructuras_contactos');
    }
}
