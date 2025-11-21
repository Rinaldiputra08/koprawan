<?php

use App\Models\MasterData\Karyawan;
use App\Models\MasterData\Voucher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_voucher', function (Blueprint $table) {
            $table->foreignId('karyawan_id')->constrained((new Karyawan())->getTable());
            $table->foreignId('voucher_id')->constrained((new Voucher())->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_voucher');
    }
}
