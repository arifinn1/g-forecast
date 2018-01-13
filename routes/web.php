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

  Route::get('/savety', 'SavetyController@index')->name('savety');

  Route::get('/ramal/bulan', 'RamalBulanController@index')->name('ramal_bulan');
  Route::post('/ramal/bulan/cari_produk', 'RamalBulanController@cari_produk')->name('rb_cari_produk');
  Route::post('/ramal/bulan/ambil_penjualan', 'RamalBulanController@ambil_penjualan')->name('ambil_penjualan');
  Route::post('/ramal/bulan/ambil_penjualan_min', 'RamalBulanController@ambil_penjualan_min')->name('ambil_penjualan_min');
  Route::post('/ramal/bulan/simpan_ramal', 'RamalBulanController@simpan_ramal')->name('simpan_ramal');
  //Route::get('/ramal/bulan/ambil_penjualan_m/{kd}', 'RamalBulanController@ambil_penjualan_m')->name('ambil_penjualan_m');

  Route::get('/lapor/bulan', 'LaporanController@bulan')->name('lapor_bulan');
  Route::get('/lapor/bulan/{tanggal}/', 'LaporanController@bulan')->name('lapor_bulan_by');
  Route::get('/lapor/cetak_dbulan/{kd}/', 'LaporanController@cetak_det_bulan')->name('cetak_det_bulan');
  Route::get('/lapor/cetak_bulan/{tanggal}/', 'LaporanController@cetak_bulan')->name('cetak_bulan');
  
  Route::get('/ramal/bulan/test_progress', 'RamalBulanController@test_progress')->name('test_progress');
  Route::get('/ramal/bulan/test_proses', 'RamalBulanController@test_proses')->name('test_proses');

  Route::get('/ramal/minggu', 'RamalMingguController@index')->name('ramal_minggu');
  Route::post('/ramal/minggu/ambil_penjualan_min', 'RamalMingguController@ambil_penjualan_min')->name('ambil_penjualan_ming_min');
  Route::post('/ramal/minggu/simpan_ramal', 'RamalMingguController@simpan_ramal')->name('simpan_ramal_ming');
  
  Route::get('/lapor/minggu', 'LaporanController@minggu')->name('lapor_minggu');
  Route::get('/lapor/minggu/{tanggal}/', 'LaporanController@minggu')->name('lapor_minggu_by');
  Route::get('/lapor/cetak_dminggu/{kd}/', 'LaporanController@cetak_det_minggu')->name('cetak_det_minggu');    
  Route::get('/lapor/cetak_minggu/{tanggal}/', 'LaporanController@cetak_minggu')->name('cetak_minggu');

  Route::get('/ramal/hari', 'RamalHariController@index')->name('ramal_hari');
  Route::post('/ramal/hari/ambil_penjualan_min', 'RamalHariController@ambil_penjualan_min')->name('ambil_penjualan_hari_min');
  Route::post('/ramal/hari/simpan_ramal', 'RamalHariController@simpan_ramal')->name('simpan_ramal_hari');

  Route::get('/lapor/hari', 'LaporanController@hari')->name('lapor_hari');
  Route::get('/lapor/hari/{tanggal}/', 'LaporanController@hari')->name('lapor_hari_by');
  Route::get('/lapor/cetak_dhari/{kd}/', 'LaporanController@cetak_det_hari')->name('cetak_det_hari');
  Route::get('/lapor/cetak_hari/{tanggal}/', 'LaporanController@cetak_hari')->name('cetak_hari');
});

Auth::routes();
Route::get('/generate/password', function(){ return bcrypt('123456789'); });
