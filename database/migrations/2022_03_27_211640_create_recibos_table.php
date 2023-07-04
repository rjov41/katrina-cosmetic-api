<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecibosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();

            $table->integer("min",false,true);
            $table->bigInteger("max",false,true);

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            $table->integer("recibo_cerrado")->default(0);
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
        Schema::dropIfExists('recibos');
    }
}
