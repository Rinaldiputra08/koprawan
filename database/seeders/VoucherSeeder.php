<?php

namespace Database\Seeders;

use App\Models\MasterData\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Voucher::factory(50)->create();
    }
}
