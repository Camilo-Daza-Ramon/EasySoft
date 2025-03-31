<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('campanas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre',200);
            $table->string('tipo',100);
            $table->string('estado',100);          
            $table->string('periodo_facturacion',20)->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_finalizacion')->nullable();
            $table->integer('cuotas_max_acuerdo')->nullable();
            $table->double('valor_pardonar_acuerdo')->nullable();
            $table->string('tipo_descuento',100)->nullable();
            $table->boolean('sin_restricciones');
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
        Schema::dropIfExists('campanas');

    }
}
