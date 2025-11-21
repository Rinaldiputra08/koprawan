<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            // $table->string('kode_menu',15)->unique();
            $table->string('nama_menu', 50);
            $table->string('url', 100);
            $table->string('icon', 50)->nullable();
            // $table->enum('level',['main_menu', 'sub_menu']);
            $table->string('jenis_bisnis')->nullable();
            $table->string('main_menu', 15)->nullable();
            $table->boolean('aktif')->default(1);
            $table->integer('no_urut')->default(0);

            $table->unique(['nama_menu', 'url']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
