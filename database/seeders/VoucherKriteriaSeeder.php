<?php

namespace Database\Seeders;

use App\Models\MasterData\VoucherKriteria;
use Illuminate\Database\Seeder;

class VoucherKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoucherKriteria::insert([
            [
                'nama' => 'minimal total belanja',
                'user_id' => '1',
                'tanggal' => now(),
                'nominal' => 100000
            ],
            [
                'nama' => 'maksimal pemakaian',
                'user_id' => '1',
                'tanggal' => now(),
                'nominal' => 1
            ],

        ]);
    }
}
