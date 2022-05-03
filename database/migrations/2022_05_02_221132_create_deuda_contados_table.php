<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeudaContadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deuda_contados', function (Blueprint $table) {
            $table->id();

            // devolucion_facturas
            $table->unsignedBigInteger("devolucion_factura_id");
            $table->foreign("devolucion_factura_id")->references("id")->on("devolucion_facturas");

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
        Schema::dropIfExists('deuda_contados');
    }
}
