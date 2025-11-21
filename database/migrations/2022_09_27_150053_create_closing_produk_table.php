<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClosingProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closing_produk', function (Blueprint $table) {
            $table->id();
            $table->integer('periode');
            $table->foreignId('produk_id')->constrained('data_produk');
            $table->integer('qty_awal');
            $table->integer('hpp_awal');
            $table->integer('qty_masuk');
            $table->integer('qty_keluar');
            $table->integer('hpp_akhir');
            $table->integer('qty_akhir');
            $table->integer('amount_akhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('closing_produk');
    }
}
