<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Esta migracion es para promociones y quedo como borrador
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();

            // productos
            $table->unsignedBigInteger("producto_id");
            $table->foreign("producto_id")->references("id")->on("productos");

            $table->integer("cantidad_aplica")->length(5);
            $table->string('codigo')->unique()->nullable();

            $table->double('porcentaje', 7, 2);

            $table->double('monto', 7, 2);
            
            $table->integer("tipo_promocion")->length(1)->default(1);

            $table->dateTime("fecha_inicio",$precision = 0);
            
            $table->dateTime("fecha_fin",$precision = 0);

            $table->integer("habilitado")->length(1)->default(1);
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
        Schema::dropIfExists('promociones');
    }
}
