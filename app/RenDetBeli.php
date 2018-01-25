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
    if($operasi=='BARU'){
      $ins = [];

      for($i=0; $i<count($r_data); $i++){
        if($r_data[$i]['rencana']>0){
          $ins[] = ['kd_rbeli' => $kd_beli,
            'kd_prod' => $r_data[$i]['kd_prod'],
            'safety_stock'=> $r_data[$i][$periode=='bulanan'? 's_bulan': 's_ming'],
            'ramalan' => $r_data[$i][$periode=='bulanan'? 'r_bulan': 'r_ming'][0],
            'jumlah' => $r_data[$i]['rencana']
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
  }
}
