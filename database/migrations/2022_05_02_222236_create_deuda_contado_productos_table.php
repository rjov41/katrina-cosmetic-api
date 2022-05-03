<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeudaContadoProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deuda_contado_productos', function (Blueprint $table) {
            $table->id();

            // devolucion_facturas
            $table->unsignedBigInteger("devolucion_producto_id");
            $table->foreign("devolucion_producto_id")->references("id")->on("devolucion_productos");

            $table->double('monto', 7, 2);
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
        Schema::dropIfExists('deuda_contado_productos');
    }
}
