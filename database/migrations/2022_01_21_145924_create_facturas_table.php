<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();

            // users
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            // clientes
            $table->unsignedBigInteger("cliente_id");
            $table->foreign("cliente_id")->references("id")->on("clientes");

            $table->double('monto', 7, 2);
            $table->double("saldo_restante", 7, 2); // es un contador de lo que falta por pagar de la factura (este sera igual al total y va ir disminuyendo hasta llegar a 0)
            // $table->string("nruc",20);
            $table->dateTime("fecha_vencimiento",$precision = 0);
            $table->double('iva', 7, 2);
            // $table->integer("tcambio")->length(1); //1 usd 2 nica
            // $table->double("monto_cambio", 7, 2);
            $table->integer("tipo_venta")->length(1); //1 credito 2 contado
            $table->integer("status_pagado")->length(1); // define si se pago una factura fue pagada en su totalidad, 0= en proceso y 1 = pagado
            $table->integer("despachado")->length(1)->default(0); // esta seccion la maneja el admin para saber si fue despachada o no la factura
            $table->integer("entregado")->length(1)->default(0); // valida si elvendedor le entrego la factura al administrador
            $table->integer("status")->length(1);  // define si la factura esta activa o no (eliminada o no) 0 = eliminada, 1= activa
            $table->timestamps();
            $table->timestamp('status_pagado_at')->default(null)->nullable(); // Fecha de cierre factura

            // $table->string("nombre_cliente",20);
            // $table->string("credito",5);
            // $table->integer("numero_factura")->length(11);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
