<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    return view('pages.auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('home', 'App\Http\Controllers\DashboardController@index')->name('home');

    Route::resource('user', UserController::class);
    Route::resource('product', \App\Http\Controllers\ProductController::class);
    Route::resource('order', \App\Http\Controllers\OrderController::class);
    Route::get('order-product', 'App\Http\Controllers\OrderController@view')->name('order-product');
    Route::resource('stock', \App\Http\Controllers\StockController::class);
    Route::get('stock-opname', 'App\Http\Controllers\StockController@index2')->name('stock-opname.index');
    Route::get('stock-opname/create', 'App\Http\Controllers\StockController@create2')->name('stock-opname.create');
    Route::post('stock-opname', 'App\Http\Controllers\StockController@store2')->name('stock-opname.store');
});
