<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RamalBulan extends Model
{
    protected $table = 'ramal_bulan';
    protected $primaryKey = 'kd';
    public $timestamps = false;

    public function simpan($data)
    {
      $datetime = date("Y-m-d H:i:s");
      $ins = [];

      for($i=0; $i<count($data); $i++){
        $ins[] = ['kd_prod' => $data[$i][8], 'dibuat' => `'`.$datetime.`'`, 'alpha' => $data[$i][4], 'gamma' => $data[$i][5], 'mape' => $data[$i][6], 'mse' => $data[$i][7], 'actual' => `'`.$data[$i][0].`'`, 'ramalan' => `'`.$data[$i][1].`'`, 'fitness' => `'`.$data[$i][2].`'`];
      }
      $insert = DB::table('ramal_bulan')->insert($ins);

      $ret = $insert ? "SUCCESS" : "FAILED";
      return $ret;
    }
}
