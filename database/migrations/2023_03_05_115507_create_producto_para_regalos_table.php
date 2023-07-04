<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoParaRegalosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_para_regalos', function (Blueprint $table) {
            $table->id();

            // producto de regalo
            $table->unsignedBigInteger("producto_id");
            $table->foreign("producto_id")->references("id")->on("productos");

            $table->integer("cantidad")->length(5);

            $table->integer("id_producto_regalo");

            $table->integer("habilitado")->length(1)->default(1);
            $table->integer("estado")->length(1)->default(1);

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
        Schema::dropIfExists('producto_para_regalos');
    }
}
