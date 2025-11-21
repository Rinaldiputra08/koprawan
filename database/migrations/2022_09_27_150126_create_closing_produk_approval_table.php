<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClosingProdukApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closing_produk_approval', function (Blueprint $table) {
            $table->id();
            $table->integer('periode');
            $table->dateTime('tanggal_closing');
            $table->foreignId('user_closing_id')->constrained('users');
            $table->string('user_closing');
            $table->boolean('proses')->default(0);
            $table->boolean('approve')->default(0);
            $table->dateTime('tanggal_approval')->nullable();
            $table->foreignId('user_approval_id')->nullable()->constrained('users');
            $table->string('user_approval')->nullable();
            $table->string('keterangan_approval')->nullable();
            $table->dateTime('tanggal_reclosing')->nullable();
            $table->foreignId('user_reclosing_id')->nullable()->constrained('users');
            $table->string('user_reclosing')->nullable();
            $table->string('keterangan_reclosing')->nullable();
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
        Schema::dropIfExists('closing_produk_approval');
    }
}
