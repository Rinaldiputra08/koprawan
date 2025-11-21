<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\UpdatePasswordController;
use App\Http\Controllers\Api\FirebaseController;
use App\Http\Controllers\Api\MasterData\KategoriController;
use App\Http\Controllers\Api\MasterData\ProdukController;
use App\Http\Controllers\Api\MasterData\RatingController;
use App\Http\Controllers\Api\MasterData\UserController;
use App\Http\Controllers\Api\MasterData\VoucherController;
use App\Http\Controllers\Api\Penjualan\CartController;
use App\Http\Controllers\Api\Penjualan\PenjualanController;
use App\Http\Controllers\Api\Titipan\TitipanController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [LoginController::class, 'loginKaryawan']);

Route::get('timeserver', function () {
    return response()->json(['time' => time()]);
});

Route::get('category', [KategoriController::class, 'get']);
Route::get('products/{type}', [ProdukController::class, 'get']);
Route::get('products/{slug}/detail', [ProdukController::class, 'detail']);

Route::middleware('auth:api')->group(function () {
    Route::post('auth/logout', [LoginController::class, 'logout']);
    Route::post('auth/update-password', [UpdatePasswordController::class, 'update']);
    Route::get('users/authenticated', [UserController::class, 'getUserAuthenticated']);
    Route::apiResource('users', UserController::class);

    Route::get('carts-count', [CartController::class, 'count']);
    Route::apiResource('carts', CartController::class);
    Route::apiResource('checkout', PenjualanController::class);
    Route::apiResource('penjualan', PenjualanController::class);
    Route::get('titipan/karyawan/{jenis}', [TitipanController::class, 'getTitipanKaryawan']);
    Route::post('titipan/approve/{titipan}', [TitipanController::class, 'approve'])->name('titipan.approve');
    Route::post('titipan/batal/{titipan}', [TitipanController::class, 'batal'])->name('titipan.batal');
    Route::apiResource('titipan', TitipanController::class);
    Route::post('rating', [RatingController::class, 'store']);
    Route::get('vouchers', [VoucherController::class, 'get']);
    Route::post('firebase', [FirebaseController::class, 'store']);
});
