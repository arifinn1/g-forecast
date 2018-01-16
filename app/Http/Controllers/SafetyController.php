<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Safety;
use App\Produk;

class SafetyController extends Controller
{
  public function index(Request $request)
  {
    $safety = new Safety();
    $produk = new Produk();
    $data = [];
    $data['safety'] = $safety->tampil_safety();
    $data['produk'] = $produk->tampil_produk_safety();
    $data['datatables'] = true;
    $data['datetimepicker'] = true;
    $data['title'] = 'Parameter Safety - Genetic Forecast';
    $data['nama'] = $request->session()->get('nama');
    $data['posisi'] = $request->session()->get('posisi');

    return view('safety/lihat', $data);
  }

  public function simpan(Request $request, Safety $safety)
  {
    $ret = "";
    $data = [];
    $data['berlaku'] =  date("Y-m-d", strtotime($request->input('berlaku')));
    $data['lead_time'] =  $request->input('lead_time');
    $data['serv_level'] = $request->input('sl');
    $data['dibuat_oleh'] = $request->session()->get('kd');
    
    if($request->input('kd')==''){
      $safety->insert($data);

      $ret = "BARU|".json_encode($safety->tampil_safety_last())."|".$data['serv_level'];
    }else{
      $data2 = $safety->find($request->input('kd'));
      $data2->berlaku = $data['berlaku'];
      $data2->lead_time = $data['lead_time'];
      $data2->serv_level = $data['serv_level'];
      $data2->dibuat_oleh = $data['dibuat_oleh'];
      $data2->save();
      $ret = "UBAH|".json_encode($safety->tampil_safety_by($request->input('kd')))."|".$data['serv_level'];
    }
    
    echo $ret;
  }

  public function hapus(Request $request, Safety $safety){
    $jml = $safety->find($request->input('kd'))->delete();
    echo $jml;
  }
}
