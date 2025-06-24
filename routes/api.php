<?php

use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\BarangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', RegisterController::class)->name('registerr');
Route::post('register1', RegisterController::class)->name('registerr1');
Route::post('login', LoginController::class)->name('loginn');
Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});
Route::post('logout', LogoutController::class)->name('logoutt');

Route::get('levels', [LevelController::class, 'index'])->name('levels.indexx');
Route::post('levels', [LevelController::class, 'store'])->name('levels.storee');
Route::get('levels/{level}', [LevelController::class, 'show'])->name('levels.showw');
Route::put('levels/{level}', [LevelController::class, 'update'])->name('levels.updatee');
Route::delete('levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroyy');

Route::get('users', [UserController::class, 'index'])->name('users.indexx');
Route::post('users', [UserController::class, 'store'])->name('users.storee');
Route::get('users/{user}', [UserController::class, 'show'])->name('users.showw');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.updatee');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroyy');

Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.indexx');
Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.storee');
Route::get('kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.showw');
Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.updatee');
Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroyy');

Route::get('barang', [BarangController::class, 'index'])->name('barang.indexx');
Route::post('barang', [BarangController::class, 'store'])->name('barang.storee');
Route::get('barang/{barang}', [BarangController::class, 'show'])->name('barang.showw');
Route::put('barang/{barang}', [BarangController::class, 'update'])->name('barang.updatee');
Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroyy');

Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.indexx');
Route::post('penjualan', [PenjualanController::class, 'storeapi'])->name('penjualan.storee');
Route::get('penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.showw');
Route::put('penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.updatee');
Route::delete('penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroyy');
