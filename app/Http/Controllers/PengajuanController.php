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
    //$data['reframal'] = $renbeli->ambil_ref_peramalan();
    //$data['produk'] = $produk->tampil_stok($data['reframal']['mingguan']['dibuat'], $data['reframal']['bulanan']['dibuat']);
    $data['produk'] = $produk->tampil_stok_by('bulan', '', '');
    $data['renbeli'] = $renbeli->tampil_semua();
    $data['datatables'] = true;
    $data['datetimepicker'] = true;
    $data['title'] = 'Firm Order (Pengajuan) - Genetic Forecast';
    $data['nama'] = $request->session()->get('g_nama');
    $data['posisi'] = $request->session()->get('g_posisi');

    return view('pengajuan/lihat', $data);
  }

  public function ambil_stok(Request $request)
  {
    $produk = new Produk();
    $data = $produk->tampil_stok_by(($request->input('periode')=='bulanan'? 'bulan':'ming'), $request->input('ref_ramal'), $request->input('kd'));

    echo json_encode($data);
  }

  public function simpan(Request $request)
  {
    $renbeli = new RenBeli();
    $kd_beli = $renbeli->simpan(
      $request->input('kd'), 
      $request->input('periode'), 
      $request->input('tgl_periode'), 
      $request->input('ref_ramal'), 
      $request->session()->get('g_kd'), 
      $request->input('ket'));

    $rendbeli = new RenDetBeli();
    $ret = $rendbeli->simpan($request->input('kd')==''? 'BARU':'UBAH', $request->input('periode'), $kd_beli, json_decode($request->input('r_data'), true));
    return redirect()->route('pengajuan')->with('pesan', 'Firm Order berhasil disimpan');
  }

  public function hapus(Request $request, RenBeli $renbeli){
    $jml = $renbeli->find($request->input('kd'))->delete();
    $rendbeli = new RenDetBeli();
    $rendbeli->where('kd_rbeli', '=', $request->input('kd'))->delete();

    echo $jml;
  }

  public function cetak(Request $request, $kd)
  {
    $renbeli = new RenBeli();
    $rendbeli = new RenDetBeli();

    $data = [];
    $data['title'] = 'Cetak Firm Order - Genetic Forecast';
    $data['renbeli'] = $renbeli->tampil_cetak($kd);
    $data['rendbeli'] = $rendbeli->tampil_cetak($kd);

    return view('pengajuan/cetak', $data);
  }
}
