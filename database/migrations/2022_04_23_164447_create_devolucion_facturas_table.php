<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolucionFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devolucion_facturas', function (Blueprint $table) {
            $table->id();

            // Facturas
            $table->unsignedBigInteger("factura_id");
            $table->foreign("factura_id")->references("id")->on("facturas");

            // users
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            $table->string('descripcion')->nullable();

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
        Schema::dropIfExists('devolucion_facturas');
    }
}
