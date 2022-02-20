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
            $table->string("nruc",20);
            $table->dateTime("fecha_vencimiento",$precision = 0);
            $table->double('iva', 7, 2);
            $table->integer("tcambio")->length(1);
            $table->integer("status_pagado")->length(1);
            $table->integer("status")->length(1);
            $table->timestamps();
            
            
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
