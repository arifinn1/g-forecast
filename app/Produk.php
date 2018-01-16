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

  public function tampil_produk($periode)
  {
    $data = DB::table("f_penj_$periode as f")
      ->select(DB::raw("p.kd_prod, p.nm_db, COUNT(f.kd_f)AS panjang"))
      ->leftJoin('d_prod as p', 'f.kd_prod', '=', 'p.kd_prod')
      ->groupBy('f.kd_prod')
      ->havingRaw('COUNT(f.kd_f) > 1')
      ->get();
    
    return $data;
  }

  public function tampil_produk_safety()
  {
    $data = DB::table("d_prod")
      ->select(DB::raw("*, 90 AS serv_level"))
      ->orderByRaw('kd_prod ASC')
      ->get();
    
    return $data;
  }

  public function tampil_produk_last($periode)
  {
    $data = NULL;
    if($periode=="bulan"){
      $data_t = DB::table("f_penj_bulan as f")
        ->select(DB::raw("MAX(CONCAT(tahun,LPAD(bulan,2,'0'))) AS last_period"))
        ->first();
      
      $data = DB::table("f_penj_bulan as f")
        ->select(DB::raw("p.kd_prod, p.nm_db, COUNT(f.kd_f)AS panjang"))
        ->leftJoin('d_prod as p', 'f.kd_prod', '=', 'p.kd_prod')
        ->whereIn('f.kd_prod', function($query) use ($data_t) {
            $query->select('kd_prod')->from('f_penj_bulan')
            ->whereRaw("CONCAT(tahun,LPAD(bulan,2,'0'))=".$data_t->last_period);
          })
        ->groupBy('f.kd_prod')
        ->havingRaw('COUNT(f.kd_f) > 1')
        ->get();
    }elseif($periode=="ming"){
      $data_t = DB::table("f_penj_ming as f")
        ->select(DB::raw("MAX(CONCAT(tahun,LPAD(minggu,2,'0'))) AS last_period"))
        ->first();
      
      $data = DB::table("f_penj_ming as f")
        ->select(DB::raw("p.kd_prod, p.nm_db, COUNT(f.kd_f)AS panjang"))
        ->leftJoin('d_prod as p', 'f.kd_prod', '=', 'p.kd_prod')
        ->whereIn('f.kd_prod',function($query) use ($data_t){
            $query->select('kd_prod')->from('f_penj_ming')
            ->whereRaw("CONCAT(tahun,LPAD(minggu,2,'0'))=".$data_t->last_period);
          })
        ->groupBy('f.kd_prod')
        ->havingRaw('COUNT(f.kd_f) > 1')
        ->get();
    }elseif($periode=="hari"){
      $data_t = DB::table("f_penj_hari as f")
        ->select(DB::raw("DATE_ADD(MAX(tgl), INTERVAL -1 MONTH) AS last_period"))
        ->first();
      
      $data = DB::table("f_penj_hari as f")
        ->select(DB::raw("p.kd_prod, p.nm_db, COUNT(f.kd_f)AS panjang"))
        ->leftJoin('d_prod as p', 'f.kd_prod', '=', 'p.kd_prod')
        ->whereIn('f.kd_prod',function($query) use ($data_t){
            $query->select('kd_prod')->from('f_penj_ming')
            ->whereRaw("tgl>='".$data_t->last_period."'");
          })
        ->groupBy('f.kd_prod')
        ->havingRaw('COUNT(f.kd_f) > 1')
        ->get();
    }

    return $data;
  }
}
