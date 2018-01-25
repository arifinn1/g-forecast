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
    $data['reframal'] = $renbeli->ambil_ref_peramalan();
    $data['produk'] = $produk->tampil_stok($data['reframal']['mingguan']['dibuat'], $data['reframal']['bulanan']['dibuat']);
    $data['renbeli'] = $renbeli->tampil_semua();
    $data['datatables'] = true;
    $data['datetimepicker'] = true;
    $data['title'] = 'Rencana Pembelian - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['posisi'] = $request->session()->get('posisi');

    return view('pengajuan/lihat', $data);
  }

  public function ambil_stok($mingguan, $bulanan)
  {
    
  }

  public function simpan(Request $request)
  {
    $renbeli = new RenBeli();
    $kd_beli = $renbeli->simpan(
      $request->input('kd'), 
      $request->input('periode'), 
      $request->input('tgl_periode'), 
      $request->input('ref_ramal'), 
      $request->session()->get('kd'), 
      $request->input('ket'));

    $rendbeli = new RenDetBeli();
    $ret = $rendbeli->simpan($request->input('kd')==''? 'BARU':'UBAH', $request->input('periode'), $kd_beli, json_decode($request->input('r_data'), true));
    return redirect()->route('pengajuan');
  }
}
