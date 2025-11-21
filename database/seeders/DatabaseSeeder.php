<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleMenuPermissionSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            KategoriSeeder::class,
            MerekSeeder::class,
            VoucherKriteriaSeeder::class,
            SetupApplicationSeeder::class,
            KaryawanSeeder::class,
            LimitKaryawanSeeder::class,
            ProdukSeeder::class,
            SupplierSeeder::class,
            VoucherSeeder::class,
        ]);
    }
}
