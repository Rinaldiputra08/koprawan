<?php

namespace Database\Factories\Promo;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiskonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nominal' => 1000,
            'tanggal_awal' => now(),
            'tanggal_akhir' => Carbon::now()->addYears(1),
            'user_id' => 1
        ];
    }
}
