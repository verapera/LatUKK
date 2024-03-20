<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;

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
Route::group(['middleware'=>'auth'],function(){
    Route::get('/', function () {
        return view('index');
    });
    Route::controller(ProdukController::class)->group(function(){
        Route::get('produk','index')->name('produk');
        Route::post('addproduk','create')->name('addproduk');
        Route::delete('deleteproduk/{produk_id}','delete')->name('deleteproduk');
        Route::get('showproduk/{produk_id}','showproduk')->name('showproduk');
        Route::put('updateproduk/{produk_id}','update')->name('updateproduk');
    });
    Route::controller(PelangganController::class)->middleware('petugas')->group(function(){
        Route::get('pelanggan','index')->name('pelanggan');
        Route::post('addpelanggan','create')->name('addpelanggan');
        Route::delete('deletepelanggan/{pelanggan_id}','delete')->name('deletepelanggan');
    });
    Route::controller(PenggunaController::class)->middleware('admin')->group(function(){
        Route::get('pengguna','index')->name('pengguna');
        Route::post('addpengguna','create')->name('addpengguna');
        Route::delete('deletepengguna/{user_id}','delete')->name('deletepengguna');
        Route::get('showpengguna/{user_id}','showpengguna')->name('showpengguna');
        Route::put('updatepengguna/{user_id}','update')->name('updatepengguna');
    });
    Route::controller(PenjualanController::class)->group(function(){
        Route::get('penjualan','index')->name('penjualan');
        Route::get('transaksi/{pelanggan_id}','transaksi')->name('transaksi');
        Route::post('addtemp/{pelanggan_id}','addtemp')->name('addtemp');
        Route::delete('deltemp/{temp_id}','deltemp')->name('deltemp');
        Route::post('bayarr/{pelanggan_id}','bayarr')->name('bayarr');
        Route::get('invoice/{kode_penjualan}','invoice')->name('invoice');
        Route::get('report/{kode_penjualan}','report')->name('report');
    });
});
Route::controller(AuthController::class)->group(function(){
    Route::get('login','showlogin')->name('login');
    Route::post('login','login');
    Route::get('logout','logout')->name('logout');
});
