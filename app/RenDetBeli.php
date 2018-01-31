<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RenDetBeli extends Model
{
  protected $table = 'r_det_beli';
  protected $primaryKey = 'kd';
  public $timestamps = false;

  public function simpan($operasi, $periode, $kd_beli, $r_data)
  {
    if($operasi=='UBAH'){
      $delete = DB::table('r_det_beli')->where('kd_rbeli', '=', $kd_beli)->delete();
    }

    $ins = [];
    for($i=0; $i<count($r_data); $i++){
      if($r_data[$i]['r_jml']>0){
        $ins[] = ['kd_rbeli' => $kd_beli,
          'kd_prod' => $r_data[$i]['kd_prod'],
          'safety_stock'=> $r_data[$i]['safety'],
          'ramalan' => $r_data[$i]['ramal'][0],
          'jumlah' => $r_data[$i]['r_jml']
        ];
      }
    }
    $ins = array_chunk($ins, 40);

    $insert = true;
    for($i=0; $i<count($ins); $i++){
      $insert = DB::table('r_det_beli')->insert($ins[$i]);
    }

    $ret = $insert ? "SUCCESS" : "FAILED";
    return $ret;
  }

  public function setujui($kd_beli, $r_data)
  {
    $ins = [];
    for($i=0; $i<count($r_data); $i++){
      if($r_data[$i]['r_jml']>0){
        DB::table('r_det_beli')
          ->where('kd_rbeli', '=', $kd_beli)
          ->where('kd_prod', '=', $r_data[$i]['kd_prod'])
          ->update([ 'jml_disetujui' => ($r_data[$i]['r_diset']==''? 0 : $r_data[$i]['r_diset']) ]);
      }
    }
  }

  public function tampil_cetak($kd)
  {
    $data = DB::table('r_det_beli as r')
      ->select(DB::raw('r.*, p.kimap, p.nm_db, p.satuan'))
      ->join('d_prod as p', 'p.kd_prod', '=', 'r.kd_prod')
      ->where('r.kd_rbeli', '=', $kd)
      ->orderBy('p.kimap')
      ->get();
    
    return $data;
  }
}
