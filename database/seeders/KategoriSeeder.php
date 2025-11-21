<?php

namespace Database\Seeders;

use App\Models\MasterData\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kategori::create([
            'nama' => 'Tas',
            'user_id' => 1,
        ]);
        Kategori::create([
            'nama' => 'Baju',
            'user_id' => 1,
        ]);
    }
}
