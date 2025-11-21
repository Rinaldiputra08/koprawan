<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->foreignId('karyawan_id')->constrained('data_karyawan');
            $table->integer('total');
            $table->integer('diskon');
            $table->integer('grand_total');
            $table->string('status');
            $table->string('jenis');
            $table->boolean('batal')->default(0);
            $table->text('keterangan_batal')->nullable();
            $table->dateTime('tanggal_batal')->nullable();
            $table->foreignId('user_batal_id')->nullable()->constrained('users');
            $table->string('user_batal');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('user_input');
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
        Schema::dropIfExists('trans_penjualan');
    }
}
