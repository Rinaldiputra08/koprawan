<?php

namespace Database\Seeders;

use App\Models\MasterData\LimitKaryawan;
use Illuminate\Database\Seeder;

class LimitKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LimitKaryawan::insert([
            [
                'karyawan_id' => 1,
                'nominal' => 5000000,
                'periode' => getCurrentPeriode(),
            ],
            [
                'karyawan_di' => 2,
                'nominal' => 3000000,
                'periode' => getCurrentPeriode(),
            ]
        ]);
    }
}
