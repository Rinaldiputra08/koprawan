<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritaAcaraGudangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_acara_gudang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('jenis');
            $table->date('tanggal_berita_acara');
            $table->text('keterangan');
            $table->foreignId('user_batal_id')->nullable()->constrained('users');
            $table->string('user_batal')->nullable();
            $table->text('keterangan_batal')->nullable();
            $table->dateTime('tanggal_batal')->nullable();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('berita_acara_gudang');
    }
}
