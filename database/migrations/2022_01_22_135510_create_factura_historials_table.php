<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaHistorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_historials', function (Blueprint $table) {
            $table->id();
            
            // Facturas
            $table->unsignedBigInteger("factura_id");
            $table->foreign("factura_id")->references("id")->on("facturas");
            $table->double('precio', 7, 2);
            
            
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
        Schema::dropIfExists('factura_historials');
    }
}
