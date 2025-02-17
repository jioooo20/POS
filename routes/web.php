<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/user', function () {
    return view('userpage');
})->name('user');

Route::get('/sales', function () {
    return view('sales');
})->name('sales');

Route::prefix('category')->group(function () {
    Route::get('/food-beverage', [CategoryController::class, 'fnb']);
    Route::get('/beauty-health', [CategoryController::class, 'beauty']);
    Route::get('/home-care', [CategoryController::class, 'homecare']);
    Route::get('/baby-kid', [CategoryController::class, 'babykid']);
});

Route::get('/user/{id?}/name/{name?}',[UserController::class, 'index']);
