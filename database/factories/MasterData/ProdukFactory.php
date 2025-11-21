<?php

namespace Database\Factories\MasterData;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $judul = $this->faker->sentence();
        return [
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'deskripsi' => $this->faker->sentence(20),
            'uuid' => Str::uuid(),
            'kode' => $this->faker->randomNumber(8, true),
            'nama' => $this->faker->sentence(3),
            'harga_beli' => 100000,
            'harga_jual' => $this->faker->randomNumber(4, true),
            'hpp' => 100000,
            'kategori_id' => 1,
            'merek_id' => 1,
            'user_id' => 1,
            'stock_free' => 20,
            'stock_fisik' => 20,
            'user_input' => $this->faker->name,
        ];
    }
}
