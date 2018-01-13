<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalHari;
use App\Produk;
use App\PenjHari;

class RamalHariController extends Controller
{
  public function index(Request $request)
  {
    $produk = new Produk();

    $data = [];
    $data['scroll'] = true;
    $data['chart'] = true;
    $data['title'] = 'Ramal Hari - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['produk'] = $produk->tampil_produk_last('hari');

    return view('ramal/hari', $data);
  }

  public function ambil_penjualan_min(Request $request)
  {
    $penjhari = new PenjHari();
    $data = $penjhari->ambil_penjualan($request->input('kd_prod'));
    $data = array_slice(json_decode(json_encode($data), true), -150);
    echo $penjhari->operasi_genetika($data, true)."||".$request->input('kd_prod')."||".count($data);
  }

  public function simpan_ramal(Request $request)
  {
    $ramalhari = new RamalHari();
    $data = json_decode($request->input('data'));
    echo $ramalhari->simpan($data);
  }
}