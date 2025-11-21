<?php

namespace Database\Factories\MasterData;

use App\Models\MasterData\Karyawan;
use App\Models\MasterData\Voucher;
use App\Models\MasterData\VoucherKriteria;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'nama' => $this->faker->sentence(3),
            'ketentuan' => $this->faker->randomElement([0, 1]),
            'kode_voucher' => strtoupper(Str::random(7)),
            'tanggal_awal' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'tanggal_akhir' => $this->faker->dateTimeBetween('+1 days', '+10 days'),
            'nominal' => $this->faker->randomNumber(random_int(5, 7)),
            'user_id' => '1',
            'jenis' => $this->faker->randomElement(['Voucher user', 'Voucher umum']),
            'tanggal' => now()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Voucher $voucher) {
            if ($voucher->ketentuan) {
                $kriteria = VoucherKriteria::all()->take($this->faker->randomElement([1, 2]));
                $voucher->kriteria()->sync($kriteria->pluck('id')->toArray());
            }

            if ($voucher->jenis == 'Voucher user') {
                $karyawan = Karyawan::all()->pluck('id')->toArray();
                $voucher->penerimaVoucher()->sync($karyawan);
            }
        });
    }
}
