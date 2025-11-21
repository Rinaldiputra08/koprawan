<?php

namespace Database\Seeders;

use App\Models\MasterData\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::factory(50)->create();
    }
}
