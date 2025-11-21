<?php

namespace Database\Seeders;

use App\Models\MasterData\DataCustomer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        DataCustomer::create([
            'uuid' => Str::uuid(),
            'nama' => $faker->name,
            'email' => $faker->email,
            'no_hp' => '083873393703',
            'jenis_kelamin' => collect(['L', 'P'])->random(),
            'tanggal_lahir' => now(),
            'password' => bcrypt('123qweA'),
        ])->alamat()->create([
            'provinsi_id' => 5, // jogja
            'provinsi' => 'DI Yogyakarta',
            'kota_id' => 39, // bantul
            'kota' => 'Bantul',
            'kode_pos' => $faker->postcode,
            'alamat' => $faker->address,
            'penerima' => $faker->name,
            'no_hp' => $faker->phoneNumber,
            'utama' => 1,
        ]);
    }
}
