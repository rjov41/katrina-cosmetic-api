<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            
            $table->string("nombre",76);
            $table->integer("telefono")->length(8);
            $table->string("direccion",140);
            $table->string("ciudad",18);
            $table->string("email",31);
            $table->string("ruc",20);
            $table->string("persona_contacto",38);
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
        Schema::dropIfExists('clientes');
    }
}
