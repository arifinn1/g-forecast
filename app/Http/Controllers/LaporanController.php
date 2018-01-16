<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalBulan;
use App\RamalMinggu;
use App\RamalHari;

class LaporanController extends Controller
{
  public function bulan(Request $request, $tanggal='')
  {
    $ramalbulan = new RamalBulan();

    $data = [];
    $data['page'] = 'lap_bulan';
    $data['chart'] = true;
    $data['datatables'] = true;
    //$data['$ex_pdf'] = true;
    $data['title'] = 'Laporan Bulanan - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['tanggal'] = $ramalbulan->tampil_tgl_ramal();
    $data['stanggal'] = $tanggal;
    $data['dramal'] = $ramalbulan->tampil_data_ramal($tanggal);

    return view('laporan/bulan', $data);
  }

  public function minggu(Request $request, $tanggal='')
  {
    $ramalminggu = new RamalMinggu();

    $data = [];
    $data['page'] = 'lap_ming';
    $data['chart'] = true;
    $data['datatables'] = true;
    $data['title'] = 'Laporan Mingguan - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['tanggal'] = $ramalminggu->tampil_tgl_ramal();
    $data['stanggal'] = $tanggal;
    $data['dramal'] = $ramalminggu->tampil_data_ramal($tanggal);

    return view('laporan/minggu', $data);
  }

  public function hari(Request $request, $tanggal='')
  {
    $ramalhari = new RamalHari();

    $data = [];
    $data['page'] = 'lap_hari';
    $data['chart'] = true;
    $data['datatables'] = true;
    $data['title'] = 'Laporan Harian - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['tanggal'] = $ramalhari->tampil_tgl_ramal();
    $data['stanggal'] = $tanggal;
    $data['dramal'] = $ramalhari->tampil_data_ramal($tanggal);

    return view('laporan/hari', $data);
  }

  public function cetak_det_bulan(Request $request, $kd)
  {
    $ramalbulan = new RamalBulan();
    
    $data = [];
    $data['title'] = 'Cetak Laporan Bulanan - Genetic Forecast';
    $data['dramal'] = $ramalbulan->tampil_det_ramal_by($kd);

    return view('laporan/cetak_det_bulan', $data);
  }

  public function cetak_det_minggu(Request $request, $kd)
  {
    $ramalminggu = new RamalMinggu();
    
    $data = [];
    $data['title'] = 'Cetak Laporan Mingguan - Genetic Forecast';
    $data['dramal'] = $ramalminggu->tampil_det_ramal_by($kd);

    return view('laporan/cetak_det_minggu', $data);
  }

  public function cetak_det_hari(Request $request, $kd)
  {
    $ramalhari = new RamalHari();
    
    $data = [];
    $data['title'] = 'Cetak Laporan Harian - Genetic Forecast';
    $data['dramal'] = $ramalhari->tampil_det_ramal_by($kd);

    return view('laporan/cetak_det_hari', $data);
  }

  public function cetak_bulan(Request $request, $tanggal)
  {
    $ramalbulan = new RamalBulan();

    $data = [];
    $data['title'] = 'Cetak Laporan Bulanan - Genetic Forecast';
    $data['dramal'] = $ramalbulan->tampil_data_ramal($tanggal);

    return view('laporan/cetak_bulan', $data);
  }

  public function cetak_minggu(Request $request, $tanggal)
  {
    $ramalminggu = new RamalMinggu();

    $data = [];
    $data['title'] = 'Cetak Laporan Mingguan - Genetic Forecast';
    $data['dramal'] = $ramalminggu->tampil_data_ramal($tanggal);

    return view('laporan/cetak_minggu', $data);
  }

  public function cetak_hari(Request $request, $tanggal)
  {
    $ramalhari = new RamalHari();

    $data = [];
    $data['title'] = 'Cetak Laporan Harian - Genetic Forecast';
    $data['dramal'] = $ramalhari->tampil_data_ramal($tanggal);

    return view('laporan/cetak_hari', $data);
  }
}
