<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTitipanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_titipan', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('judul');
            $table->string('slug');
            $table->string('nama');
            $table->integer('harga_jual');
            $table->integer('stock_fisik');
            $table->integer('stock_free');
            $table->text('deskripsi');
            $table->double('sharing_profit');
            $table->double('rating');
            $table->integer('rating_count');
            $table->integer('total_rating');
            $table->integer('terjual');
            $table->boolean('approval')->nullable();
            $table->date('tanggal_approval')->nullable();
            $table->string('keterangan_approval')->nullable();
            $table->foreignId('user_approve_id')->nullable()->constrained('data_karyawan');
            $table->string('user_approve_nama')->nullable();
            $table->boolean('batal');
            $table->string('keterangan_batal');
            $table->foreignId('karyawan_id')->constrained('data_karyawan');
            $table->dateTime('tanggal_awal_penjualan');
            $table->dateTime('tanggal_akhir_penjualan');
            $table->dateTime('tanggal')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_titipan');
    }
}
