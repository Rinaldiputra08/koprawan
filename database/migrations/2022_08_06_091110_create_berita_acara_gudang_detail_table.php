<?php

use App\Models\Gudang\BeritaAcaraGudang;
use App\Models\MasterData\Produk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritaAcaraGudangDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_acara_gudang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_gudang_id')->constrained((new BeritaAcaraGudang())->getTable());
            $table->foreignId('produk_id')->constrained((new Produk())->getTable());
            $table->integer('qty');
            $table->text('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berita_acara_gudang_detail');
    }
}
