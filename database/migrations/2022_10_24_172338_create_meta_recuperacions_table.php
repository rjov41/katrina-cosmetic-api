<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaRecuperacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_recuperacions', function (Blueprint $table) {
            $table->id();

            // usuarios
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            $table->double('monto_meta', 7, 2);

            // $table->timestamp("fecha_asignacion")->nullable(); // lo saqueporque me confundi y pense que era la meta. Pero es la recuperacion

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
        Schema::dropIfExists('meta_recuperacions');
    }
}
