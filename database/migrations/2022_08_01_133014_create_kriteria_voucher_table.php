<?php

use App\Models\MasterData\Voucher;
use App\Models\MasterData\VoucherKriteria;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKriteriaVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kriteria_voucher', function (Blueprint $table) {
            $table->foreignId('voucher_id')->constrained((new Voucher())->getTable());
            $table->foreignId('kriteria_id')->constrained((new VoucherKriteria())->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kriteria_voucher');
    }
}
