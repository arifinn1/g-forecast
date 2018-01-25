<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RenBeli extends Model
{
  protected $table = 'r_beli';
  protected $primaryKey = 'kd';
  public $timestamps = false;

  public function tampil_semua()
  {
    $data = DB::table("r_beli as r")
      ->select(DB::raw("r.*, ap.nama as nama_peng, ac.nama as nama_acc"))
      ->join('akun as ap', 'ap.kd', '=', 'r.dibuat_oleh')
      ->leftJoin('akun as ac', 'ac.kd', '=', 'r.disetujui_oleh')
      ->orderBy('r.tgl_periode', 'desc')
      ->get();
    
    return $data;
  }

  public function kode_gen()
  {
    $data = DB::table('r_beli')
      ->select(DB::raw('(IFNULL(MAX(kd),0)+1) AS kode'))
      ->orderBy('kd', 'desc')
      ->first();
    return $data->kode;
  }

  public function simpan($kd, $periode, $tgl_periode, $ref_ramal, $dibuat, $ket)
  {
    $ret = '';
    $data = [];
    $data['kd'] = $kd;
    $data['periode'] = $periode;
    $data['tgl_periode'] = $tgl_periode;
    $data['ref_ramal'] = $ref_ramal;
    $data['dibuat_oleh'] = $dibuat;
    $data['ket'] = $ket;

    if($kd==''){
      $data['kd'] = $this->kode_gen();
      $data['tgl_buat'] = date("Y-m-d H:i:s");
      $data['tgl_status'] = $data['tgl_buat'];
      $data['status'] = 'menunggu';

      $this->insert($data);
    }else{
      $data2 = $this->find($kd);
      $data2->periode = $periode;
      $data2->tgl_periode = $tgl_periode;
      $data2->ref_ramal = $ref_ramal;
      $data2->dibuat_oleh = $dibuat;
      $data2->ket = $ket;
      
      $data2->tgl_buat = date("Y-m-d H:i:s");
      $data2->tgl_status = $data2->tgl_buat;
      $data2->save();
    }

    return $data['kd'];
  }

  public function ambil_ref_peramalan()
  {
    $ret = array(
      'mingguan' => array('dibuat'=>'', 'r_awal'=>'', 'r_ming'=>0, 'r_jumlah'=>0), 
      'bulanan' => array('dibuat'=>'', 'r_awal'=>'', 'r_jumlah'=>0)
    );

    /*$data = DB::table('ramal_hari')
      ->select(DB::raw('dibuat, r_awal, r_jumlah'))
      ->groupBy('dibuat')
      ->orderBy('dibuat', 'desc')
      ->limit(1)
      ->first();
    $ret['harian'] = $data->dibuat;*/

    $data = DB::table('ramal_ming')
      ->select(DB::raw('dibuat, r_awal, r_ming, r_jumlah'))
      ->groupBy('dibuat')
      ->orderBy('dibuat', 'desc')
      ->limit(1)
      ->first();
    $ret['mingguan']['dibuat'] = $data->dibuat;
    $ret['mingguan']['r_awal'] = $data->r_awal;
    $ret['mingguan']['r_ming'] = $data->r_ming;
    $ret['mingguan']['r_jumlah'] = $data->r_jumlah;

    $data = DB::table('ramal_bulan')
      ->select(DB::raw('dibuat, r_awal, r_jumlah'))
      ->groupBy('dibuat')
      ->orderBy('dibuat', 'desc')
      ->limit(1)
      ->first();
    $ret['bulanan']['dibuat'] = $data->dibuat;
    $ret['bulanan']['r_awal'] = $data->r_awal;
    $ret['bulanan']['r_jumlah'] = $data->r_jumlah;

    return $ret;
  }
}
