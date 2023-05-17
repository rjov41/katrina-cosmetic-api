<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string("tipo",20);
            $table->string("descripcion",80);
            $table->double('monto_menor', 7, 2);
            $table->double('monto_maximo', 7, 2);
            $table->integer("condicion")->length(3);
            // $table->integer("valor_dias");
            $table->integer("estado")->length(1);
            $table->timestamps();
        });
    }

    // C=$300
    // B=$500
    // A=$800
    // AA=$801 a más
    // Lista negra: Los clientes que caigan en mora de 60-90 días == 0
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
