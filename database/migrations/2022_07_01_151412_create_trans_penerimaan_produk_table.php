<?php

use App\Models\MasterData\Produk;
use App\Models\MasterData\Supplier;
use App\Models\Pembelian\PemesananProduk;
use App\Models\Pembelian\PenerimaanProduk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPenerimaanProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_penerimaan_produk', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nomor', 20)->unique();
            $table->foreignId('pemesanan_produk_id')->nullable()->constrained((new PemesananProduk())->getTable());
            $table->foreignId('supplier_id')->constrained((new Supplier())->getTable());
            $table->integer('ppn');
            $table->integer('total');
            $table->date('tanggal_penerimaan');
            $table->date('tanggal_tagihan')->nullable();
            $table->string('nomor_tagihan', 30)->nullable();
            $table->text('keterangan')->nullable();
            $table->dateTime('tanggal_batal')->nullable();
            $table->foreignId('user_batal_id')->nullable()->constrained('users');
            $table->string('user_batal')->nullable();
            $table->text('keterangan_batal')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('user_input');
            $table->dateTime('tanggal')->useCurrent();
        });

        Schema::create('trans_penerimaan_produk_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_produk_id')->constrained((new PenerimaanProduk())->getTable());
            $table->foreignId('produk_id')->constrained((new Produk())->getTable());
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('diskon');
            $table->integer('sub_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_penerimaan_produk');
        Schema::dropIfExists('trans_penerimaan_produk_detail');
    }
}
