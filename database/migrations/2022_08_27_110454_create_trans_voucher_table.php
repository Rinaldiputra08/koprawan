<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_voucher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('trans_penjualan');
            $table->foreignId('voucher_id')->constrained('data_voucher');
            $table->foreignId('karyawan_id')->constrained('data_karyawan');
            $table->string('nama');
            $table->integer('nominal');
            $table->dateTime('tanggal')->useCurrent();
            $table->boolean('batal')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_voucher');
    }
}
