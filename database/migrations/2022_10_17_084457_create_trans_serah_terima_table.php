<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransSerahTerimaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_serah_terima', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->foreignId('karyawan_id')->constrained('data_karyawan');
            $table->foreignId('penjualan_id')->constrained('trans_penjualan');
            $table->integer('total');
            $table->integer('grand_total');
            $table->boolean('status')->default(0);
            $table->date('tanggal_penerimaan');
            // $table->text('keterangan')->nullable();
            $table->dateTime('tanggal_batal')->nullable();
            $table->foreignId('user_batal_id')->nullable()->constrained('users');
            $table->string('user_batal')->nullable();
            $table->text('keterangan_batal')->nullable();
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
        Schema::dropIfExists('trans_serah_terima');
    }
}