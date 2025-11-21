<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_rating', function (Blueprint $table) {
            $table->id();
            $table->integer('produk_id');
            $table->string('produk_type');
            $table->foreignId('karyawan_id')->constrained('data_karyawan');
            $table->foreignId('penjualan_detail_id')->constrained('trans_penjualan_detail');
            $table->integer('rating');
            $table->text('komentar');
            $table->timestamp('tanggal')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_rating');
    }
}
