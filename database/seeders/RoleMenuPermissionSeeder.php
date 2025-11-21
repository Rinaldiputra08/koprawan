<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class RoleMenuPermissionSeeder extends Seeder
{
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
                Role::create(['name' => 'administrator']);
                Role::create(['name' => 'user']);
                Cache::forget('navigation');

                $konfigurasiMain = Menu::create(['icon' => 'settings', 'nama_menu' => 'Konfigurasi', 'url' => 'konfigurasi', 'main_menu' => null]);
                Permission::create(['name' => 'read konfigurasi'])->assignRole('administrator')->menus()->attach($konfigurasiMain);

                $konfigurasi = $konfigurasiMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Users', 'url' => 'konfigurasi/users']);
                Permission::create(['name' => 'create konfigurasi/users'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'read konfigurasi/users'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'update konfigurasi/users'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'delete konfigurasi/users'])->assignRole('administrator')->menus()->attach($konfigurasi);

                $konfigurasi = $konfigurasiMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Akses User', 'url' => 'konfigurasi/akses-user']);
                Permission::create(['name' => 'read konfigurasi/akses-user'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'update konfigurasi/akses-user'])->assignRole('administrator')->menus()->attach($konfigurasi);

                $konfigurasi = $konfigurasiMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Akses Role', 'url' => 'konfigurasi/akses-role']);
                Permission::create(['name' => 'read konfigurasi/akses-role'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'update konfigurasi/akses-role'])->assignRole('administrator')->menus()->attach($konfigurasi);

                $konfigurasi = $konfigurasiMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Menu', 'url' => 'konfigurasi/menu']);
                Permission::create(['name' => 'create konfigurasi/menu'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'read konfigurasi/menu'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'update konfigurasi/menu'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'delete konfigurasi/menu'])->assignRole('administrator')->menus()->attach($konfigurasi);

                $konfigurasi = $konfigurasiMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Role', 'url' => 'konfigurasi/roles']);
                Permission::create(['name' => 'create konfigurasi/roles'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'read konfigurasi/roles'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'update konfigurasi/roles'])->assignRole('administrator')->menus()->attach($konfigurasi);

                $konfigurasi = $konfigurasiMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Permission', 'url' => 'konfigurasi/permissions']);
                Permission::create(['name' => 'create konfigurasi/permissions'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'read konfigurasi/permissions'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'update konfigurasi/permissions'])->assignRole('administrator')->menus()->attach($konfigurasi);

                $konfigurasi = $konfigurasiMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Setup Aplikasi', 'url' => 'konfigurasi/setup-aplikasi']);
                Permission::create(['name' => 'create konfigurasi/setup-aplikasi'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'read konfigurasi/setup-aplikasi'])->assignRole('administrator')->menus()->attach($konfigurasi);
                Permission::create(['name' => 'update konfigurasi/setup-aplikasi'])->assignRole('administrator')->menus()->attach($konfigurasi);

                $masterDataMain = Menu::create(['icon' => 'book', 'nama_menu' => 'Master Data', 'url' => 'master-data', 'main_menu' => null]);
                Permission::create(['name' => 'read master-data'])->assignRole('administrator')->menus()->attach($konfigurasiMain);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Produk', 'url' => 'master-data/produk']);
                Permission::create(['name' => 'create master-data/produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/produk'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Kategori', 'url' => 'master-data/kategori']);
                Permission::create(['name' => 'create master-data/kategori'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/kategori'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/kategori'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/kategori'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Merek', 'url' => 'master-data/merek']);
                Permission::create(['name' => 'create master-data/merek'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/merek'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/merek'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/merek'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Supplier', 'url' => 'master-data/supplier']);
                Permission::create(['name' => 'create master-data/supplier'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/supplier'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/supplier'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/supplier'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Karyawan', 'url' => 'master-data/karyawan']);
                Permission::create(['name' => 'create master-data/karyawan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/karyawan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/karyawan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/karyawan'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Limit Karyawan', 'url' => 'master-data/limit-karyawan']);
                Permission::create(['name' => 'create master-data/limit-karyawan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/limit-karyawan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/limit-karyawan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/limit-karyawan'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Voucher', 'url' => 'master-data/voucher']);
                Permission::create(['name' => 'create master-data/voucher'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/voucher'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/voucher'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/voucher'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterDataMain->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Voucher Kriteria', 'url' => 'master-data/voucher-kriteria']);
                Permission::create(['name' => 'create master-data/voucher-kriteria'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read master-data/voucher-kriteria'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update master-data/voucher-kriteria'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete master-data/voucher-kriteria'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterTitipan = Menu::create(['icon' => 'briefcase', 'nama_menu' => 'Titipan', 'url' => 'titipan', 'main_menu' => null]);
                Permission::create(['name' => 'read titipan'])->assignRole('administrator')->menus()->attach($masterTitipan);

                $masterdata = $masterTitipan->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Produk Titipan', 'url' => 'titipan/produk-titipan']);
                Permission::create(['name' => 'read titipan/produk-titipan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update titipan/produk-titipan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'approve titipan/produk-titipan'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterPenjualan = Menu::create(['icon' => 'shopping-bag', 'nama_menu' => 'Penjualan', 'url' => 'penjualan', 'main_menu' => null]);
                Permission::create(['name' => 'read penjualan'])->assignRole('administrator')->menus()->attach($masterPenjualan);

                $masterdata = $masterPenjualan->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Penjualan Langsung', 'url' => 'penjualan/penjualan-langsung']);
                Permission::create(['name' => 'create penjualan/penjualan-langsung'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read penjualan/penjualan-langsung'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update penjualan/penjualan-langsung'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete penjualan/penjualan-langsung'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'cancel penjualan/penjualan-langsung'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'print penjualan/penjualan-langsung'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterPenjualan->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Retur Penjualan', 'url' => 'penjualan/retur-penjualan']);
                Permission::create(['name' => 'create penjualan/retur-penjualan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read penjualan/retur-penjualan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update penjualan/retur-penjualan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete penjualan/retur-penjualan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'cancel penjualan/retur-penjualan'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'print penjualan/retur-penjualan'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterPenjualan->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Serah Terima Barang', 'url' => 'penjualan/serah-terima-barang']);
                Permission::create(['name' => 'create penjualan/serah-terima-barang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read penjualan/serah-terima-barang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update penjualan/serah-terima-barang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete penjualan/serah-terima-barang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'cancel penjualan/serah-terima-barang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'print penjualan/serah-terima-barang'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterPromo = Menu::create(['icon' => 'book', 'nama_menu' => 'Promo', 'url' => 'promo', 'main_menu' => null]);
                Permission::create(['name' => 'read promo'])->assignRole('administrator')->menus()->attach($konfigurasiMain);

                $masterdata = $masterPromo->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Diskon', 'url' => 'promo/diskon']);
                Permission::create(['name' => 'create promo/diskon'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read promo/diskon'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update promo/diskon'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete promo/diskon'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterPembelian = Menu::create(['icon' => 'shopping-cart', 'nama_menu' => 'Pembelian', 'url' => 'pembelian', 'main_menu' => null]);
                Permission::create(['name' => 'read pembelian'])->assignRole('administrator')->menus()->attach($masterPembelian);

                $masterdata = $masterPembelian->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Pemesanan Produk', 'url' => 'pembelian/pemesanan-produk']);
                Permission::create(['name' => 'create pembelian/pemesanan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read pembelian/pemesanan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update pembelian/pemesanan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete pembelian/pemesanan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'cancel pembelian/pemesanan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'print pembelian/pemesanan-produk'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterdata = $masterPembelian->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Penerimaan Produk', 'url' => 'pembelian/penerimaan-produk']);
                Permission::create(['name' => 'create pembelian/penerimaan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read pembelian/penerimaan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update pembelian/penerimaan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete pembelian/penerimaan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'cancel pembelian/penerimaan-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'print pembelian/penerimaan-produk'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterGudang = Menu::create(['icon' => 'box', 'nama_menu' => 'Gudang', 'url' => 'gudang', 'main_menu' => null]);
                Permission::create(['name' => 'read gudang'])->assignRole('administrator')->menus()->attach($masterGudang);

                $masterdata = $masterGudang->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Berita Acara', 'url' => 'gudang/berita-acara-gudang']);
                Permission::create(['name' => 'create gudang/berita-acara-gudang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read gudang/berita-acara-gudang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update gudang/berita-acara-gudang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete gudang/berita-acara-gudang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'cancel gudang/berita-acara-gudang'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'print gudang/berita-acara-gudang'])->assignRole('administrator')->menus()->attach($masterdata);

                // 
                $masterdata = $masterGudang->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Master Stock Opname', 'url' => 'gudang/stock-opname']);
                Permission::create(['name' => 'read gudang/stock-opname'])->assignRole('administrator')->menus()->attach($masterdata);

                $masterClosing = Menu::create(['icon' => 'server', 'nama_menu' => 'Closing', 'url' => 'closing', 'main_menu' => null]);
                Permission::create(['name' => 'read closing'])->assignRole('administrator')->menus()->attach($masterClosing);

                $masterdata = $masterClosing->subMenus()->create(['icon' => 'circle', 'nama_menu' => 'Cosing Produk', 'url' => 'closing/closing-produk']);
                Permission::create(['name' => 'create closing/closing-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'read closing/closing-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'update closing/closing-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'delete closing/closing-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'cancel closing/closing-produk'])->assignRole('administrator')->menus()->attach($masterdata);
                Permission::create(['name' => 'print closing/closing-produk'])->assignRole('administrator')->menus()->attach($masterdata);
        }
}
