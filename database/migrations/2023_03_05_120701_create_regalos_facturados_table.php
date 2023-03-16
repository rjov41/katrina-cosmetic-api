<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegalosFacturadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regalos_facturados', function (Blueprint $table) {
            $table->id();

            // productos Facturados
            $table->unsignedBigInteger("factura_detalle_id");
            $table->foreign("factura_detalle_id")->references("id")->on("factura_detalles");

            // productos Facturados
            $table->unsignedBigInteger("regalo_id");
            $table->foreign("regalo_id")->references("id")->on("producto_para_regalos");

            $table->integer("cantidad_regalada")->length(5);

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
        Schema::dropIfExists('regalos_facturados');
    }
}
