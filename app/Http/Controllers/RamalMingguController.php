<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalMinggu;
use App\Produk;
use App\PenjMinggu;

class RamalMingguController extends Controller
{
  public function index(Request $request)
  {
    $produk = new Produk();

    $data = [];
    $data['scroll'] = true;
    $data['chart'] = true;
    $data['title'] = 'Ramal Minggu - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['produk'] = $produk->tampil_produk_last('ming');

    return view('ramal/minggu', $data);
  }

  public function ambil_penjualan_min(Request $request)
  {
    $penjminggu = new PenjMinggu();
    $data = $penjminggu->ambil_penjualan($request->input('kd_prod'));
    $data = json_decode(json_encode($data), true);
    echo $penjminggu->operasi_genetika($data, true)."||".$request->input('kd_prod')."||".count($data);
  }

  public function simpan_ramal(Request $request)
  {
    $ramalminggu = new RamalMinggu();
    $data = json_decode($request->input('data'));
    echo $ramalminggu->simpan($data);
  }
}
