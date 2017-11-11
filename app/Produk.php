<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
  protected $table = 'd_prod';
  protected $primaryKey = 'kd_prod';
  public $timestamps = false;

  public function cari_produk($kata_kunci)
  {
    $data = DB::table("d_prod as p")
      ->select("p.kd_prod", "p.nm_db", "p.nm_lain", "f.panjang")
      ->join(DB::raw("(SELECT kd_prod, COUNT(kd_f) as panjang FROM f_penj_bulan GROUP BY kd_prod) f"), function($join) {
        $join->on('p.kd_prod','f.kd_prod');
      })
      ->whereRaw("MATCH (p.nm_db,p.nm_lain) AGAINST ('$kata_kunci' IN NATURAL LANGUAGE MODE)")
      ->get();
    
    return $data;
  }
}
