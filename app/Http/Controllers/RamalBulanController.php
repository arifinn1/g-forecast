<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalBulan;
use App\Produk;
use App\PenjBulan;

class RamalBulanController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data['chart'] = true;
        $data['title'] = 'Ramal Bulan - Genetic Forecast';
        $data['nama'] = $request->session()->get('nama');

        return view('ramal/bulan', $data);
    }

    public function cari_produk(Request $request)
    {
      $produk = new Produk();
      echo json_encode($produk->cari_produk($request->input('keyword')));
    }

    public function ambil_penjualan(Request $request)
    {
      $penjbulan = new PenjBulan();
      echo json_encode($penjbulan->ambil_penjualan($request->input('kd_prod')));
    }
}
