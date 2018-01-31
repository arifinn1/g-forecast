<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalMinggu;
use App\Produk;
use App\PenjMinggu;
use App\Safety;

class RamalMingguController extends Controller
{
  public function index(Request $request)
  {
    $produk = new Produk();

    $data = [];
    $data['scroll'] = true;
    $data['chart'] = true;
    $data['title'] = 'Ramal Minggu - Genetic Forecast';
    $data['nama'] = $request->session()->get('g_nama');
    $data['posisi'] = $request->session()->get('g_posisi');
    $data['produk'] = $produk->tampil_produk_last('ming');

    return view('ramal/minggu', $data);
  }

  public function ambil_penjualan_min(Request $request)
  {
    $penjminggu = new PenjMinggu();
    $data = $penjminggu->ambil_penjualan($request->input('kd_prod'));
    $data = json_decode(json_encode($data), true);
    
    $safety = new Safety();
    $safety_stock = $safety->calc_safety_stock_adv('ming', $request->input('kd_prod'));

    echo $penjminggu->operasi_genetika($data, true)."||".$request->input('kd_prod')."||".count($data)."||".$safety_stock."||".$this->get_r_awal($data[count($data)-1]['tahun'], $data[count($data)-1]['minggu']);
  }

  public function get_r_awal($year, $week)
  {
    if($week==52){
      $year++;
      $week = 1;
    }else{ $week++; }

    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*($week-1))+1-$day)*24*3600;

    return date('Y-m-d', $time).'||'.$week;
  }

  public function simpan_ramal(Request $request)
  {
    $ramalminggu = new RamalMinggu();
    $data = json_decode($request->input('data'));
    echo $ramalminggu->simpan($data);
  }
}
