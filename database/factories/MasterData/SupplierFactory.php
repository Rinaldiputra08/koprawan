<?php

namespace Database\Factories\MasterData;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->company,
            'nomor_telepon' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
            'aktif' => $this->faker->randomElement([1, 0]),
            'user_id' => 1
        ];
    }
}
