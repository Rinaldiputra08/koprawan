<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataFotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_foto', function(Blueprint $table){
            $table->id();
            $table->string('nama_file');
            $table->bigInteger('referensi_id');
            $table->string('referensi_type');
            $table->boolean('thumbnail')->default(0);
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
        Schema::dropIfExists('data_foto');
    }
}
