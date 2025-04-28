<?php

use App\Http\Controllers\Api\LevelController;
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

Route::post('register', \App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('login', \App\Http\Controllers\Api\LoginController::class)->name('login');
Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});
Route::post('logout', \App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::get('levels', [LevelController::class, 'index'])->name('levels.index');
Route::post('levels', [LevelController::class, 'store'])->name('levels.store');
Route::get('levels/{level}', [LevelController::class, 'show'])->name('levels.show');
Route::put('levels/{level}', [LevelController::class, 'update'])->name('levels.update');
Route::delete('levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroy');
