<?php

namespace Database\Seeders;

use App\Models\SetupApplication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SetupApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('config');
        $setups = [
            ['name' => 'purchase_order', 'value' => 'true'],
            ['name' => 'tanggal_closing_transaksi', 'value' => '20'],
            ['name' => 'sharing_profit', 'value' => '0.1']

        ];

        SetupApplication::insert($setups);
    }
}
