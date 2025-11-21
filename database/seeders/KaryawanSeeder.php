<?php

namespace Database\Seeders;

use App\Models\MasterData\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Karyawan::insert([
            [
                'uuid' => Str::uuid(),
                'qr_file' => 'K0nYh1Cod1eTaZ0JlkOG2BSR0DJD3HLs0PVb4HUX1FVW5fR1.png',
                'nik' => '100011',
                'nama' => 'MUHAMAD ABDULLAH',
                'divisi' => 'IT',
                'password' => bcrypt('123qweA')
            ],
            [

                'uuid' => Str::uuid(),
                'qr_file' => ' U0myW1CgOnT1UYgqJwi0bM2030Ov4qRdSVV1iJkMM5Bda3iq.png',
                'nik' => '100013',
                'nama' => 'AZIS SOIP',
                'divisi' => 'SERVICE',
                'password' => bcrypt('123qweA')
            ]
        ]);
    }
}
