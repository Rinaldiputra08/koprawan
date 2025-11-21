<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPenjualanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_penjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('trans_penjualan');
            $table->integer('produk_id');
            $table->string('produk_type');
            $table->integer('harga');
            $table->integer('hpp');
            $table->integer('qty');
            $table->integer('total_harga');
            $table->foreignId('diskon_id')->nullable()->constrained('data_diskon');
            $table->integer('nominal_diskon')->default(0);
            $table->integer('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_penjualan_detail');
    }
}
