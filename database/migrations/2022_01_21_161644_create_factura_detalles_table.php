<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_detalles', function (Blueprint $table) {
            $table->id();


            // productos
            $table->unsignedBigInteger("producto_id");
            $table->foreign("producto_id")->references("id")->on("productos");

            // Facturas
            $table->unsignedBigInteger("factura_id");
            $table->foreign("factura_id")->references("id")->on("facturas");

            $table->integer("cantidad")->length(5);
            $table->double('precio', 7, 2);
            $table->double('precio_unidad', 7, 2)->default(0);
            // $table->integer("porcentaje");
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
        Schema::dropIfExists('factura_detalles');
    }
}
