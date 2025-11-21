<?php

namespace Database\Seeders;

use App\Models\MasterData\Produk;
use App\Models\Promo\Diskon;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Produk::factory(50)->create();
        Produk::factory(8)->has(Diskon::factory(1))->create();
    }
}
