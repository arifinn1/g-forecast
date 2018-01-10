<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalBulan;

class LaporanController extends Controller
{
  public function bulan(Request $request, $tanggal='')
  {
    $ramalbulan = new RamalBulan();

    $data = [];
    $data['chart'] = true;
    $data['datatables'] = true;
    //$data['$ex_pdf'] = true;
    $data['title'] = 'Lapor Bulan - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['tanggal'] = $ramalbulan->tampil_tgl_ramal();
    $data['stanggal'] = $tanggal;
    $data['dramal'] = $ramalbulan->tampil_data_ramal($tanggal);

    return view('laporan/bulan', $data);
  }

  public function cetak_det_bulan(Request $request, $kd)
  {
    $ramalbulan = new RamalBulan();
    
    $data = [];
    $data['title'] = 'Cetak Lapor Bulan - Genetic Forecast';
    $data['dramal'] = $ramalbulan->tampil_det_ramal_by($kd);

    return view('laporan/cetak_det_bulan', $data);
  }

  public function cetak_bulan(Request $request, $tanggal)
  {
    $ramalbulan = new RamalBulan();

    $data = [];
    $data['title'] = 'Cetak Lapor Bulan - Genetic Forecast';
    $data['dramal'] = $ramalbulan->tampil_data_ramal($tanggal);

    return view('laporan/cetak_bulan', $data);
  }
}
