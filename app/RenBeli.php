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
    $data = DB::table("r_beli")
      ->orderBy('tgl_periode', 'desc')
      ->get();
    
    return $data;
  }

  public function ambil_ref_peramalan()
  {
    $ret = array('harian'=>'', 'mingguan'=>'', 'bulanan'=>'');

    $data = DB::table('ramal_hari')
      ->select('dibuat')
      ->groupBy('dibuat')
      ->orderBy('dibuat', 'desc')
      ->limit(1)
      ->first();
    $ret['harian'] = $data->dibuat;

    $data = DB::table('ramal_ming')
      ->select('dibuat')
      ->groupBy('dibuat')
      ->orderBy('dibuat', 'desc')
      ->limit(1)
      ->first();
    $ret['mingguan'] = $data->dibuat;

    $data = DB::table('ramal_bulan')
      ->select('dibuat')
      ->groupBy('dibuat')
      ->orderBy('dibuat', 'desc')
      ->limit(1)
      ->first();
    $ret['bulanan'] = $data->dibuat;

    return $ret;
  }
}
