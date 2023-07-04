<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            
            $table->string("marca",30);
            $table->string("modelo",43);
            $table->integer("stock");
            // $table->integer("minimo")->length(1);
            $table->double('precio', 7, 2);
            // $table->integer('comision');
            $table->string("linea",50);
            $table->string("descripcion",200);
            $table->integer("estado")->length(1);
            
            $table->timestamps();
        });
    }
    
    //  
     
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
