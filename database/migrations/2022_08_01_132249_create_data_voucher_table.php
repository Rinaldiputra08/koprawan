<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_voucher', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('nama');
            $table->boolean('ketentuan');
            $table->string('kode_voucher')->unique();
            $table->dateTime('tanggal_awal');
            $table->dateTime('tanggal_akhir');
            $table->integer('nominal');
            $table->foreignId('user_id')->constrained((new User())->getTable());
            $table->string('jenis');
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
        Schema::dropIfExists('data_voucher');
    }
}
