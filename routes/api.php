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

Route::post('register', RegisterController::class)->name('register');
Route::post('register1', RegisterController::class)->name('register1');
Route::post('login', LoginController::class)->name('login');
Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});
Route::post('logout', LogoutController::class)->name('logout');

Route::get('levels', [LevelController::class, 'index'])->name('levels.index');
Route::post('levels', [LevelController::class, 'store'])->name('levels.store');
Route::get('levels/{level}', [LevelController::class, 'show'])->name('levels.show');
Route::put('levels/{level}', [LevelController::class, 'update'])->name('levels.update');
Route::delete('levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroy');

Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
Route::get('kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');
Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

Route::get('barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
Route::get('barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
Route::put('barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::post('penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
Route::get('penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
Route::put('penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update');
Route::delete('penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
