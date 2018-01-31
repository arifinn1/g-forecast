<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PenjHari;
use App\PenjMinggu;
use App\PenjBulan;
use App\Jadwal;
use DateTime;

class KalkulasiController extends Controller
{
  public function index(Request $request)
  {
    $jwl = new Jadwal();
    $data = [];
    $dt_day = $jwl->ambil_up_terakhir();
    $day = new DateTime($dt_day->up_terakhir);

    $data['up_day'] = $day->format("D, j F Y");
    $data['hari'] = $dt_day->hari;
    $data['minggu'] = $dt_day->minggu;
    $data['bulan'] = $dt_day->bulan;
    $data['title'] = 'Kalkulasi - Genetic Forecast';
    $data['nama'] = $request->session()->get('g_nama');
    $data['posisi'] = $request->session()->get('g_posisi');

    return view('kalkulasi/lihat', $data);
  }

  public function import()
  {
    $p_hari = new PenjHari();
    $p_ming = new PenjMinggu();
    $p_bulan = new PenjBulan();
    $jwl = new Jadwal();

    $day = $jwl->ambil_up_terakhir();
    //echo $day;

    $date = new DateTime($day->up_terakhir);
    $week = $date->format("w") - 1;
    $week = $week == -1 ? 6 : $week;
    $week = date('Y-m-d', strtotime($day->up_terakhir.' - '.$week.' days'));
    //echo '<br>'.$week;

    $month = date('Y-m-01', strtotime($day->up_terakhir));
    //echo '<br>'.$month;

    $ret = array( 'hari' => $p_hari->import($day->up_terakhir),
      'minggu' => $p_ming->import($week),
      'bulan' => $p_bulan->import($month),
      'jumlah' => []
    );

    $jwl->update_up_terakhir($day->kd);
    $ret['jumlah'] = $jwl->ambil_up_terakhir();

    echo json_encode($ret);
  }
}
