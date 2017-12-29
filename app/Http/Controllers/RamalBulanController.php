<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalBulan;
use App\Produk;
use App\PenjBulan;
use Session;
use Response;

class RamalBulanController extends Controller
{
    public function index(Request $request)
    {
      $produk = new Produk();

      $data = [];
      //$data['chart'] = true;
      $data['title'] = 'Ramal Bulan - Genetic Forecast';
      $data['nama'] = $request->session()->get('nama');
      $data['produk'] = $produk->tampil_produk();

      return view('ramal/bulann', $data);
    }

    public function cari_produk(Request $request)
    {
      $produk = new Produk();
      echo json_encode($produk->cari_produk($request->input('keyword')));
    }

    public function ambil_penjualan(Request $request)
    {
      $penjbulan = new PenjBulan();
      $data = $penjbulan->ambil_penjualan($request->input('kd_prod'));
      $data = json_decode(json_encode($data), true);
      echo json_encode($data)."||".($penjbulan->operasi_genetika($data));
    }

    public function ambil_penjualan_m(String $kd)
    {
      $penjbulan = new PenjBulan();
      $data = $penjbulan->ambil_penjualan($kd);

      $data = json_decode(json_encode($data), true);
      //$data2 = $penjbulan->resolve_missing_val($data);

      echo "<pre>";
      print_r($data);
      //print_r($data2);
      echo "</pre>";

      $penjbulan->operasi_genetika($data);
      //echo json_encode($data)."||".($penjbulan->operasi_genetika($data));
    }

    public function test_progress()
    {
      if (Session::has('progress')){
        return Response::json(Session::get('progress'));
      } else {
        return Response::json(0);
      }
    }

    public function test_proses()
    {
      Session::put('progress', 0);
      Session::save();

      for($i=0; $i<5; $i++){
        sleep(1);
        Session::put('progress', $i+1);
        Session::save();
      }
    }
}
