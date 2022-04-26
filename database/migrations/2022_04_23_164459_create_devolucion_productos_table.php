<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolucionProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devolucion_productos', function (Blueprint $table) {
            $table->id();

            // productos
            $table->unsignedBigInteger("factura_detalle_id");
            $table->foreign("factura_detalle_id")->references("id")->on("factura_detalles");

            // users
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            $table->string('descripcion')->nullable();
            $table->integer("cantidad")->length(5);

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
        Schema::dropIfExists('devolucion_productos');
    }
}
