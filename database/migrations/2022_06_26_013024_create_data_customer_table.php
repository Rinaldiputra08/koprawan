<?php

use App\Models\MasterData\DataCustomer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_customer', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->string('no_hp')->unique();
            $table->string('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable();
            $table->timestamp('tanggal')->useCurrent();
        });

        Schema::create('data_customer_alamat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained((new DataCustomer())->getTable());
            $table->bigInteger('provinsi_id');
            $table->string('provinsi')->nullable();
            $table->bigInteger('kota_id');
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos');
            $table->text('alamat');
            $table->string('penerima');
            $table->string('no_hp');
            $table->string('utama');
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
        Schema::dropIfExists('data_customer');
        Schema::dropIfExists('data_customer_alamat');
    }
}
