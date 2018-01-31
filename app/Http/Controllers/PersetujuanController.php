<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produk;
use App\RenBeli;
use App\RenDetBeli;

class PersetujuanController extends Controller
{
  public function index(Request $request)
  {
    $produk = new Produk();
    $renbeli = new RenBeli();
    $data = [];
    $data['produk'] = $produk->tampil_stok_by('bulan', '', '', false);
    $data['renbeli'] = $renbeli->tampil_semua();
    $data['datatables'] = true;
    $data['datetimepicker'] = true;
    $data['title'] = 'Firm Order (Persetujuan) - Genetic Forecast';
    $data['nama'] = $request->session()->get('g_nama');
    $data['posisi'] = $request->session()->get('g_posisi');

    return view('persetujuan/lihat', $data);
  }

  public function ambil_stok(Request $request)
  {
    $produk = new Produk();
    $data = $produk->tampil_stok_by(($request->input('periode')=='bulanan'? 'bulan':'ming'), $request->input('ref_ramal'), $request->input('kd'));

    echo json_encode($data);
  }

  public function setujui(Request $request)
  {
    $renbeli = new RenBeli();
    $renbeli->where('kd', '=', $request->input('kd'))->update([
      'status' => 'disetujui',
      'disetujui_oleh' => $request->session()->get('g_kd'),
      'tgl_status' => date("Y-m-d H:i:s"),
      'ket' => $request->input('ket')
    ]);

    $rendbeli = new RenDetBeli();
    $ret = $rendbeli->setujui($request->input('kd'), json_decode($request->input('r_data'), true));
    return redirect()->route('persetujuan')->with('pesan', 'Firm Order berhasil disetujui');
  }

  public function tolak(Request $request, RenBeli $renbeli){
    $jml = $renbeli->where('kd', '=', $request->input('kd'))->update([
      'status' => 'ditolak',
      'disetujui_oleh' => $request->session()->get('g_kd'),
      'tgl_status' => date("Y-m-d H:i:s"),
      'ket' => $request->input('ket')
    ]);
    
    $rendbeli = new RenDetBeli();
    $rendbeli->where('kd_rbeli', '=', $request->input('kd'))->update(['jml_disetujui' => 0]);

    echo $jml;
  }
}
