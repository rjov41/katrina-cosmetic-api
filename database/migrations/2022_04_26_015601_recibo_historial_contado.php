<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReciboHistorialContado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibo_historial_contado', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("numero",false,true);

            $table->unsignedBigInteger("recibo_id");
            $table->foreign("recibo_id")->references("id")->on("recibos");

            // $table->unsignedBigInteger("factura_historial_id");
            // $table->foreign("factura_historial_id")->references("id")->on("factura_historials");

            // Facturas
            $table->unsignedBigInteger("factura_id");
            $table->foreign("factura_id")->references("id")->on("facturas");

            $table->string("rango",200);

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
        Schema::dropIfExists('recibo_historial_contado');
    }
}
