<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RamalHari extends Model
{
  protected $table = 'ramal_hari';
  protected $primaryKey = 'kd';
  public $timestamps = false;

  public function simpan($data)
  {
    $datetime = date("Y-m-d H:i:s");
    $ins = [];

    for($i=0; $i<count($data); $i++){
      if(count($data[$i])>0){
        $ins[] = ['kd_prod' => $data[$i][8], 'dibuat' => `'`.$datetime.`'`, 'panjang'=> $data[$i][9], 'alpha' => $data[$i][4], 'gamma' => $data[$i][5], 'mape' => $data[$i][6], 'mse' => $data[$i][7], 'actual' => `'`.$data[$i][0].`'`, 'ramalan' => `'`.$data[$i][1].`'`, 'fitness' => `'`.$data[$i][2].`'`];
      }
    }
    $ins = array_chunk($ins, 40);

    for($i=0; $i<count($ins); $i++){
      $insert = DB::table('ramal_hari')->insert($ins[$i]);
    }

    $ret = $insert ? "SUCCESS" : "FAILED";
    return $ret;
  }

  public function tampil_tgl_ramal()
  {
    $data = DB::table("ramal_hari")
      ->select(DB::raw("dibuat, COUNT(kd_prod)AS jumlah"))
      ->groupBy('dibuat')
      ->get();
    
    return $data;
  }

  public function tampil_data_ramal($tanggal)
  {
    $data = DB::table("ramal_hari as r")
      ->select(DB::raw("r.kd, r.kd_prod, r.dibuat, p.nm_db, p.satuan, r.alpha, r.gamma, r.mape, r.actual, r.ramalan, r.fitness"))
      ->leftJoin('d_prod as p', 'r.kd_prod', '=', 'p.kd_prod')
      ->where('r.dibuat', $tanggal)
      ->get();
    
    return $data;
  }

  public function tampil_det_ramal_by($kd)
  {
    $data = DB::table("ramal_hari as r")
      ->select(DB::raw("r.kd_prod, r.dibuat, p.nm_db, p.satuan, r.alpha, r.gamma, r.mape, r.mse, r.actual, r.ramalan, r.fitness"))
      ->leftJoin('d_prod as p', 'r.kd_prod', '=', 'p.kd_prod')
      ->where('r.kd', $kd)
      ->get();
    
    return $data;
  }
}