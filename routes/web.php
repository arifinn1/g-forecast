<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/akun', 'AkunController@index')->name('lihat_akun');
    Route::post('/akun/simpan', 'AkunController@simpan')->name('simpan_akun');
    Route::post('/akun/hapus', 'AkunController@hapus')->name('hapus_akun');

    Route::get('/ganti_pass', 'AkunController@ganti_pass')->name('ganti_pass');
    Route::post('/ganti_pass', 'AkunController@proses_ganti_pass')->name('p_ganti_pass');

    Route::get('/jadwal', 'JadwalController@index')->name('jadwal');
    Route::post('/jadwal/simpan', 'JadwalController@simpan')->name('simpan_jadwal');
    Route::post('/jadwal/hapus', 'JadwalController@hapus')->name('hapus_jadwal');

    Route::get('/ramal/bulan', 'RamalBulanController@index')->name('ramal_bulan');    
});

Auth::routes();
Route::get('/generate/password', function(){ return bcrypt('123456789'); });
