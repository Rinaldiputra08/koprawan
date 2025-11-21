<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransSerahTerimaDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_serah_terima_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serah_terima_id')->constrained('trans_serah_terima');
            $table->foreignId('produk_id')->constrained('data_produk');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('diskon');
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
        Schema::dropIfExists('trans_serah_terima_detail');
    }
}