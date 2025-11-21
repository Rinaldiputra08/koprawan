<?php

namespace Database\Seeders;

use App\Models\MasterData\Merek;
use Illuminate\Database\Seeder;

class MerekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Merek::insert([
            [
                'nama' => 'GUCCI',
                'user_id' => 1
            ],
            [
                'nama' => 'YSL',
                'user_id' => 1
            ],

        ]);
    }
}
