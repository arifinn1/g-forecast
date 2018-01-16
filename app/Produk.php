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

  public function tampil_semua()
  {
    /* SELECT pd.produk_id, p.nama, p.dus, sum(case when pd.satuan<=1 and p.dus>1 then km.jmlbrg/p.dus else km.jmlbrg end) as jumlah FROM produk_diorder pd, 
    (SELECT l.* FROM suratjalan s, logsrj l WHERE s.id=l.idsrj AND s.date>='2017-03-01') km, produk p 
    WHERE pd.id=km.idbrg and pd.produk_id=p.id group by pd.produk_id

    select m_p.id, m_p.nama, m_p.dus, ifnull(m_s.jumlah,0)as jumlah from produk m_p left join (SELECT pd.produk_id, sum(case when pd.satuan<=1 and 
    p.dus>1 then km.jmlbrg/p.dus else km.jmlbrg end) as jumlah FROM produk_diorder pd, (SELECT l.* FROM suratjalan s, logsrj l WHERE s.id=l.idsrj 
    AND s.date>='2017-03-01') km, produk p 
    WHERE pd.id=km.idbrg and pd.produk_id=p.id group by pd.produk_id) m_s on(m_p.id=m_s.produk_id)
     */

    $data = DB::table("pelumas.produk as m_p")
      ->select(DB::raw("m_p.id, m_p.nama, m_p.dus, ifnull(m_s.jumlah,0)as jumlah"))
      ->leftJoin(DB::raw("(select pd.produk_id, sum(case when pd.satuan<=1 and p.dus>1 then km.jmlbrg/p.dus else km.jmlbrg end) as jumlah 
          from pelumas.produk_diorder pd, (select l.* from pelumas.suratjalan s, pelumas.logsrj l where s.id=l.idsrj and 
          s.date>='2017-03-01') km, pelumas.produk p where pd.id=km.idbrg and pd.produk_id=p.id group by pd.produk_id) m_s"), function($join) {
        $join->on('m_p.id', '=', 'm_s.produk_id');
      })
      ->orderByRaw('m_p.id ASC')
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
