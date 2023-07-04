<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrecuenciasFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frecuencias_facturas', function (Blueprint $table) {
            $table->id();
            $table->integer("dias")->length(3);
            $table->string("descripcion",80);
            $table->integer("estado")->length(1);
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
        Schema::dropIfExists('frecuencias_facturas');
    }
}
