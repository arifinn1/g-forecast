<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PenjBulan extends Model
{
  protected $table = 'f_penj_bulan';
  protected $primaryKey = 'kd_f';
  public $timestamps = false;

  public function ambil_penjualan($kd_prod)
  {
    $data = DB::table("f_penj_bulan")
      ->whereRaw("kd_prod=$kd_prod")
      ->orderBy('tahun', 'ASC')
      ->orderBy('bulan', 'ASC')
      ->get();
    
    return $data;
  }
}
