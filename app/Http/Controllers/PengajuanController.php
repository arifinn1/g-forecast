<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produk;
use App\RenBeli;
use App\RenDetBeli;

class PengajuanController extends Controller
{
  public function index(Request $request)
  {
    $produk = new Produk();
    $renbeli = new RenBeli();
    $data = [];
    $data['produk'] = $produk->tampil_semua();
    $data['renbeli'] = $renbeli->tampil_semua();
    $data['reframal'] = $renbeli->ambil_ref_peramalan();
    $data['datatables'] = true;
    $data['datetimepicker'] = true;
    $data['title'] = 'Rencana Pembelian - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');

    return view('pengajuan/lihat', $data);
  }
}
