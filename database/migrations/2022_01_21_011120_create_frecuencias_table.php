<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrecuenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frecuencias', function (Blueprint $table) {
            $table->id();
            
            // $table->unsignedBigInteger("categoria_id");
            // $table->foreign("categoria_id")->references("id")->on("categorias");
            $table->string("descripcion",80);
            $table->integer("dias")->length(3);
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
        Schema::dropIfExists('frecuencias');
    }
}
