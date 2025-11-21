<?php

use App\Models\MasterData\Karyawan;
use App\Models\MasterData\Produk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained((new Karyawan())->getTable());
            $table->bigInteger('produk_id');
            $table->string('produk_type');
            $table->integer('qty');
            $table->boolean('terjual')->default(0);
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
        Schema::dropIfExists('trans_cart');
    }
}
