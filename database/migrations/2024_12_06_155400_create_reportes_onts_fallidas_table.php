<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportesOntsFallidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportes_onts_fallidas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ClienteId');
            $table->unsignedBigInteger('Identificacion');
            $table->string('ONT_Serial', 50);
            $table->string('mensaje');
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
        Schema::dropIfExists('reportes_onts_fallidas');
    }
}
