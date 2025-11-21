<?php

use App\Models\MasterData\Produk;
use App\Models\MasterData\Supplier;
use App\Models\Pembelian\PemesananProduk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPemesananProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_pemesanan_produk', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nomor', 20)->unique();
            $table->foreignId('supplier_id')->constrained((new Supplier())->getTable());
            $table->integer('ppn');
            $table->integer('total');
            $table->date('tanggal_pemesanan');
            $table->text('keterangan')->nullable();
            $table->dateTime('tanggal_batal')->nullable();
            $table->foreignId('user_batal_id')->nullable()->constrained('users');
            $table->string('user_batal')->nullable();
            $table->text('keterangan_batal')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('user_input');
            $table->boolean('penerimaan')->default(0);
            $table->dateTime('tanggal')->useCurrent();
        });

        Schema::create('trans_pemesanan_produk_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_produk_id')->constrained((new PemesananProduk())->getTable());
            $table->foreignId('produk_id')->constrained((new Produk())->getTable());
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('diskon');
            $table->integer('sub_total');
            $table->boolean('penerimaan')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_pemesanan_produk');
    }
}
