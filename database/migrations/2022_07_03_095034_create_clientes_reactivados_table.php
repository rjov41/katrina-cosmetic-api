<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesReactivadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_reactivados', function (Blueprint $table) {
            $table->id();

            // usuarios
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            // clientes
            $table->unsignedBigInteger("cliente_id");
            $table->foreign("cliente_id")->references("id")->on("clientes");

            // Facturas
            $table->unsignedBigInteger("factura_id");
            $table->foreign("factura_id")->references("id")->on("facturas");

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
        Schema::dropIfExists('clientes_reactivados');
    }
}
