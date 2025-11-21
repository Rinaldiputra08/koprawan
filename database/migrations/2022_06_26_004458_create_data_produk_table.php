<?php

use App\Models\MasterData\Kategori;
use App\Models\MasterData\Merek;
use App\Models\MasterData\Produk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_merek', function(Blueprint $table){
            $table->id();
            $table->string('nama');
            $table->boolean('aktif')->default(1);
            $table->foreignId('user_id')->constrained();
            $table->dateTime('tanggal')->useCurrent();
        });

        Schema::create('data_kategori', function(Blueprint $table){
            $table->id();
            $table->string('nama');
            $table->boolean('aktif')->default(1);
            $table->foreignId('user_id')->constrained();
            $table->dateTime('tanggal')->useCurrent();
        });
        
        Schema::create('data_produk', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->text('judul');
            $table->text('slug');
            $table->string('kode');
            $table->string('nama');
            $table->text('deskripsi');
            $table->integer('harga_beli');
            $table->integer('harga_jual'); 
            $table->integer('hpp_sebelum');
            $table->integer('hpp');
            $table->integer('stock_free');
            $table->integer('stock_fisik');
            $table->integer('terjual');
            $table->integer('rating');
            $table->integer('rating_count');
            $table->integer('total_rating');
            $table->foreignId('kategori_id')->constrained((new Kategori())->getTable());
            $table->foreignId('merek_id')->constrained((new Merek())->getTable());
            $table->date('tanggal_beli_akhir')->nullable();
            $table->date('tanggal_jual_akhir')->nullable();
            $table->boolean('aktif')->default(1);
            $table->foreignId('user_id')->constrained();
            $table->string('user_input');
            $table->dateTime('tanggal')->useCurrent();
        });

        

        Schema::create('data_diskon', function(Blueprint $table){
            $table->id();
            $table->foreignId('produk_id')->constrained((new Produk())->getTable());
            $table->integer('nominal');
            $table->dateTime('tanggal_awal');
            $table->dateTime('tanggal_akhir');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('data_merek');
        Schema::dropIfExists('data_kategori');
        Schema::dropIfExists('data_produk');
        Schema::dropIfExists('data_diskon');
    }
}
