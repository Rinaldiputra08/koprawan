<?php

use App\Http\Controllers\{NotificationController, DashboardController, LoginAsController, LoginGoogleController, NotificationReadController, orderController};
use App\Http\Controllers\Auth\{LoginAppController, LoginController};
use App\Http\Controllers\Closing\ClosingProdukController;
use App\Http\Controllers\Gudang\BeritaAcaraGudangController;
use App\Http\Controllers\Gudang\BeritaAcaraGudangPrintController;
use App\Http\Controllers\Gudang\StockOpnameController;
use App\Http\Controllers\Konfigurasi\{AksesRoleController, AksesUserController, MenuController, PermissionController, RoleController, SetupApplicationController, UserController};
use App\Http\Controllers\MasterData\{KaryawanController, KategoriController, LimitKaryawanController, MerekController, ProdukController, SupplierController, VoucherController, VoucherKriteriaController};
use App\Http\Controllers\Migrasi\MigrasiKaryawanController;
use App\Http\Controllers\Pembelian\PemesananProdukController;
use App\Http\Controllers\Pembelian\PemesananProdukPrintController;
use App\Http\Controllers\Pembelian\PenerimaanProdukController;
use App\Http\Controllers\Pembelian\PenerimaanProdukPrintController;
use App\Http\Controllers\Penjualan\PenjualanLangsungController;
use App\Http\Controllers\Penjualan\ReturPenjualanController;
use App\Http\Controllers\Penjualan\SerahTerimaBarangController;
use App\Http\Controllers\Promo\DiskonController;
use App\Http\Controllers\Titipan\TitipanController;
use App\Models\Closing\ClosingProduk;
use App\Models\Closing\ClosingProdukApproval;
use App\Models\Gudang\BeritaAcaraGudang;
use App\Models\MasterData\LimitKaryawan;
use App\Models\Penjualan\PenjualanLangsung;
use Illuminate\Support\Facades\{Auth, Route};

/** atuhentication */
Auth::routes([
    'register' => false,
    'login' => false,
    'verify' => true
]);

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('post.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// login with google
Route::get('/auth/google', [LoginGoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [LoginGoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

/** end authentication */

Route::middleware(['auth', 'verified', 'logoff'])->group(function () {
    Route::get('/orders', [orderController::class, 'index'])->name('order');
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard.index');
        Route::get('/detail/{jenis}', [DashboardController::class, 'detail']);
        Route::get('/komparasi', [DashboardController::class, 'komparasi']);
        Route::get('/outstanding', [DashboardController::class, 'outstanding']);
        Route::get('/pencapaian', [DashboardController::class, 'pencapaian']);
        Route::get('/leads/{jenis}', [DashboardController::class, 'leads']);
    });

    // notification
    Route::post('notification', NotificationReadController::class)->name('notification.read');
    Route::get('notification', [NotificationController::class, 'global'])->name('notification');
    Route::get('notification/other', [NotificationController::class, 'other'])->name('notification.other');

    Route::get('login-as', [LoginAsController::class, 'get'])->name('login-as');
    Route::post('login-as-process', [LoginAsController::class, 'process'])->name('login-as-post');

    // Konfigurasi
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::prefix('konfigurasi')->group(function () {
        Route::resource('akses-role', AksesRoleController::class);
        Route::resource('akses-user', AksesUserController::class)->except(['show', 'destroy', 'create', 'store']);

        Route::resource('users', UserController::class)->names('users');
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);

        Route::put('menu/sort', [MenuController::class, 'sort'])->middleware('can:update konfigurasi/menu');
        Route::resource('menu', MenuController::class)->except(['show']);
        Route::resource('setup-aplikasi', SetupApplicationController::class)->except(['show', 'destroy']);
    });

    Route::prefix('master-data')->group(function () {
        Route::name('master-data.')->group(function () {
            Route::get('produk/cari', [ProdukController::class, 'find']);
            Route::resource('produk', ProdukController::class)->except(['destroy']);
            Route::resource('kategori', KategoriController::class)->except(['destroy', 'show']);
            Route::resource('merek', MerekController::class)->except(['destroy', 'show']);
            Route::resource('supplier', SupplierController::class)->except(['destroy', 'show']);
            Route::resource('voucher', VoucherController::class)->except(['destroy', 'show']);
            Route::resource('karyawan', KaryawanController::class)->except(['destroy']);
            Route::prefix('limit-karyawan')->group(function () {
                Route::get('filter', [LimitKaryawanController::class, 'filter']);
                Route::get('cari-karyawan/{divisi}', [LimitKaryawanController::class, 'listKaryawan']);
            });
            Route::resource('limit-karyawan', LimitKaryawanController::class)->except(['destroy']);
            Route::prefix('voucher')->group(function () {
                Route::name('voucher.')->group(function () {
                    Route::get('pilih-user/{voucher}', [VoucherController::class, 'pilihUser'])->name('pilih-user');
                    Route::get('cari-user', [VoucherController::class, 'findUser']);
                    Route::post('store-pemakai/{voucher}', [VoucherController::class, 'storePemakai'])->name('store-pemakai');
                });
            });
            Route::resource('voucher-kriteria', VoucherKriteriaController::class)->except(['destroy', 'show']);
        });
    });

    Route::prefix('promo')->group(function () {
        Route::name('promo.')->group(function () {
            Route::get('diskon/list-produk', [DiskonController::class, 'listProduk'])->name('diskon.list-produk');
            Route::resource('diskon', DiskonController::class)->except(['destroy', 'show']);
        });
    });

    Route::prefix('pembelian')->group(function () {
        Route::name('pembelian.')->group(function () {
            Route::prefix('pemesanan-produk')->group(function () {
                Route::get('cetak/{pemesananProduk:uuid}', PemesananProdukPrintController::class)->name('pemesanan-produk.cetak');
                Route::put('batal/{pemesananProduk}', [PemesananProdukController::class, 'batal'])->name('pemesanan-produk.batal');
                Route::get('cari', [PemesananProdukController::class, 'find']);
            });
            Route::resource('pemesanan-produk', PemesananProdukController::class)->except(['destroy', 'edit', 'update']);

            Route::prefix('penerimaan-produk')->group(function () {
                Route::get('cetak/{penerimaanProduk:uuid}', PenerimaanProdukPrintController::class)->name('penerimaan-produk.cetak');
                Route::put('batal/{penerimaanProduk}', [PenerimaanProdukController::class, 'batal'])->name('penerimaan-produk.batal');
                Route::get('cari', [PenerimaanProdukController::class, 'find']);
            });
            Route::resource('penerimaan-produk', PenerimaanProdukController::class)->except(['destroy']);
        });
    });

    Route::prefix('gudang')->group(function () {
        Route::name('gudang.')->group(function () {
            Route::prefix('berita-acara-gudang')->group(function () {
                Route::get('cetak/{berita_acara_gudang:nomor}', BeritaAcaraGudangPrintController::class)->name('berita-acara-gudang.cetak');
                Route::put('batal/{berita_acara_gudang}', [BeritaAcaraGudangController::class, 'batal'])->name('berita-acara-gudang.batal');
            });

            Route::resource('berita-acara-gudang', BeritaAcaraGudangController::class)->except(['destroy']);

            Route::get('stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname');
        });
    });

    Route::prefix('penjualan')->group(function () {

        Route::name('penjualan.')->group(function () {
            Route::prefix('penjualan-langsung')->group(function () {
                Route::get('scan-qr/{code}', [PenjualanLangsungController::class, 'cekNik']);
                Route::get('cari-produk', [PenjualanLangsungController::class, 'findProduk']);
                Route::put('batal/{penjualan_langsung}', [PenjualanLangsungController::class, 'batal'])->name('penjualan-langsung.batal');
            });

            Route::prefix('serah-terima-barang')->group(function () {
                Route::get('cari-penjualan', [SerahTerimaBarangController::class, 'findPenjualan']);
                Route::get('data-penjualan/{id}', [SerahTerimaBarangController::class, 'dataPenjualan']);
            });

            Route::get('cari-transaksi', [ReturPenjualanController::class, 'findTransaksi']);

            Route::resource('penjualan-langsung', PenjualanLangsungController::class)->except(['destroy']);
            Route::resource('retur-penjualan', ReturPenjualanController::class);
            Route::resource('serah-terima-barang', SerahTerimaBarangController::class);
        });
    });

    Route::prefix('titipan')->group(function () {
        Route::name('titipan.')->group(function () {
            Route::get('produk-titipan/approve/{titipan}', [TitipanController::class, 'approve'])->name('titipan.approve');
            Route::put('produk-titipan/approve/{titipan}', [TitipanController::class, 'postApprove'])->name('post-approve');
            ;
            Route::resource('produk-titipan', TitipanController::class)->except(['destroy']);
        });
    });

    Route::prefix('closing')->group(function () {
        Route::name('closing.')->group(function () {
            Route::get('get-data/{periode}', [ClosingProdukController::class, 'getData']);
            Route::get('closing-produk', [ClosingProdukController::class, 'index']);
            Route::post('closing-produk', [ClosingProdukController::class, 'proses']);
            Route::get('get-closed/{periode}', [ClosingProdukController::class, 'getCLosed']);
            Route::get('approval-closed/{periode}', [ClosingProdukController::class, 'approvalClosed']);
            Route::put('post-approval/{periode}', [ClosingProdukController::class, 'postApproval']);
        });
    });
});
