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

  public function tampil_stok_old($minggu, $bulan)
  {
    /* SELECT pd.produk_id, p.nama, p.dus, sum(case when pd.satuan<=1 and p.dus>1 then km.jmlbrg/p.dus else km.jmlbrg end) as jumlah FROM produk_diorder pd, (SELECT l.* FROM suratjalan s, logsrj l WHERE s.id=l.idsrj AND s.date>='2017-03-01') km, produk p WHERE pd.id=km.idbrg and pd.produk_id=p.id group by pd.produk_id

    select m_p.id, m_p.nama, m_p.dus, ifnull(m_s.jumlah,0)as jumlah from produk m_p left join (SELECT pd.produk_id, sum(case when pd.satuan<=1 and p.dus>1 then km.jmlbrg/p.dus else km.jmlbrg end) as jumlah FROM produk_diorder pd, (SELECT l.* FROM suratjalan s, logsrj l WHERE s.id=l.idsrj AND s.date>='2017-03-01') km, produk p WHERE pd.id=km.idbrg and pd.produk_id=p.id group by pd.produk_id) m_s on(m_p.id=m_s.produk_id)
     */

    $data = DB::table("d_prod as d_p")
      ->select(DB::raw("d_p.kd_prod, d_p.nm_db, d_p.satuan, ifnull(m_s.jumlah,0)as jumlah, r_m.ramalan as r_ming, r_m.safety_stock as s_ming, r_b.ramalan as r_bulan, r_b.safety_stock as s_bulan"))
      ->leftJoin(DB::raw("(select pd.produk_id, sum(case when pd.satuan<=1 and p.dus>1 then km.jmlbrg/p.dus else km.jmlbrg end) as jumlah 
          from pelumas.produk_diorder pd, (select l.* from pelumas.suratjalan s, pelumas.logsrj l where s.id=l.idsrj and 
          s.date>='2017-03-01') km, pelumas.produk p where pd.id=km.idbrg and pd.produk_id=p.id group by pd.produk_id) m_s"), function($join) {
        $join->on('d_p.kd_prod', '=', 'm_s.produk_id');
      })
      ->leftJoin(DB::raw("(select kd_prod, ramalan, safety_stock from ramal_ming where dibuat='$minggu') r_m"), function($join) {
        $join->on('d_p.kd_prod', '=', 'r_m.kd_prod');
      })
      ->leftJoin(DB::raw("(select kd_prod, ramalan, safety_stock from ramal_bulan where dibuat='$bulan') r_b"), function($join) {
        $join->on('d_p.kd_prod', '=', 'r_b.kd_prod');
      })
      ->orderByRaw('d_p.kd_prod ASC')
      ->get();
    
    foreach( $data as $baris ){
      if(!is_null($baris->r_ming)){ $baris->r_ming = array_slice(json_decode($baris->r_ming), -4); }
      if(!is_null($baris->r_bulan)){ $baris->r_bulan = array_slice(json_decode($baris->r_bulan), -4); }
    }
    
    return $data;
  }

  public function tampil_stok($minggu, $bulan)
  {
    /*
    barang masuk    
    SELECT pd.produk_id, p.nama, p.dus, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM produk_diorder pd, produk p, logsrj l, suratjalan s WHERE pd.id=l.idbrg AND l.idsrj=s.id AND pd.produk_id=p.id AND s.gudangasal<=1 AND MONTH(s.date)=12 AND YEAR(s.date)=2017 GROUP BY pd.produk_id

    barang keluar
    SELECT pd.produk_id, p.nama, p.dus, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM produk_diorder pd, produk p, logsrj l, suratjalan s WHERE pd.produk_id=p.id AND pd.id=l.idbrg AND l.idsrj=s.id AND MONTH(s.date)=12 AND YEAR(s.date)=2017 AND l.idcust>100 GROUP BY pd.produk_id
    */
    $data_t = DB::table("pelumas.suratjalan")
      ->select(DB::raw("DATE_FORMAT(max(date), '%Y-%m-01') as tgl"))
      ->first();
    
    $data = DB::table("d_prod as d_p")
      ->select(DB::raw("d_p.kd_prod, d_p.nm_db, d_p.satuan, (ifnull(m_sm.jumlah,0) - ifnull(m_sk.jumlah,0)) as jumlah, ifnull(m_sm.jumlah,0)as masuk, ifnull(m_sk.jumlah,0)as keluar, r_m.ramalan as r_ming, r_m.safety_stock as s_ming, r_b.ramalan as r_bulan, r_b.safety_stock as s_bulan"))
      ->leftJoin(DB::raw("(SELECT pd.produk_id, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM pelumas.produk_diorder pd, pelumas.produk p, pelumas.logsrj l, pelumas.suratjalan s WHERE pd.id=l.idbrg AND l.idsrj=s.id AND pd.produk_id=p.id AND s.gudangasal<=1 AND s.date>='".$data_t->tgl."' GROUP BY pd.produk_id) m_sm"), function($join) {
        $join->on('d_p.kd_prod', '=', 'm_sm.produk_id');
      })
      ->leftJoin(DB::raw("(SELECT pd.produk_id, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM pelumas.produk_diorder pd, pelumas.produk p, pelumas.logsrj l, pelumas.suratjalan s WHERE pd.produk_id=p.id AND pd.id=l.idbrg AND l.idsrj=s.id AND l.idcust>100 AND s.date>='".$data_t->tgl."' GROUP BY pd.produk_id) m_sk"), function($join) {
        $join->on('d_p.kd_prod', '=', 'm_sk.produk_id');
      })
      ->leftJoin(DB::raw("(select kd_prod, ramalan, safety_stock from ramal_ming where dibuat='$minggu') r_m"), function($join) {
        $join->on('d_p.kd_prod', '=', 'r_m.kd_prod');
      })
      ->leftJoin(DB::raw("(select kd_prod, ramalan, safety_stock from ramal_bulan where dibuat='$bulan') r_b"), function($join) {
        $join->on('d_p.kd_prod', '=', 'r_b.kd_prod');
      })
      ->orderByRaw('d_p.kd_prod ASC')
      ->get();
    
    foreach( $data as $baris ){
      if(!is_null($baris->r_ming)){ $baris->r_ming = array_slice(json_decode($baris->r_ming), -4); }
      if(!is_null($baris->r_bulan)){ $baris->r_bulan = array_slice(json_decode($baris->r_bulan), -4); }
    }
    
    return $data;
  }

  public function tampil_stok_by($periode, $tgl, $kd='', $min=false)
  {
    $data_t = DB::table("pelumas.suratjalan")
      ->select(DB::raw("DATE_FORMAT(max(date), '%Y-%m-01') as tgl"))
      ->first();
    $data_tg = DB::table("ramal_".$periode)
      ->select(DB::raw("dibuat, r_awal".($periode=='ming'? ', r_ming':'')))
      ->whereRaw("dibuat like '$tgl%'")
      ->orderByRaw('dibuat DESC')
      ->first();
    
    if($data_tg){ $tgl = $data_tg->dibuat; }
    
    $data = [];
    if(!$min){
      $data = DB::table("d_prod as d_p")
        ->select(DB::raw("d_p.kd_prod, d_p.nm_db, d_p.satuan, (ifnull(m_sm.jumlah,0) - ifnull(m_sk.jumlah,0)) as jumlah, ifnull(m_sm.jumlah,0)as masuk, ifnull(m_sk.jumlah,0)as keluar, rml.ramalan as ramal, rml.safety_stock as safety, rb.jumlah as r_jml, rb.jml_disetujui as r_diset"))
        ->leftJoin(DB::raw("(SELECT pd.produk_id, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM pelumas.produk_diorder pd, pelumas.produk p, pelumas.logsrj l, pelumas.suratjalan s WHERE pd.id=l.idbrg AND l.idsrj=s.id AND pd.produk_id=p.id AND s.gudangasal<=1 AND s.date>='".$data_t->tgl."' GROUP BY pd.produk_id) m_sm"), function($join) {
          $join->on('d_p.kd_prod', '=', 'm_sm.produk_id');
        })
        ->leftJoin(DB::raw("(SELECT pd.produk_id, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM pelumas.produk_diorder pd, pelumas.produk p, pelumas.logsrj l, pelumas.suratjalan s WHERE pd.produk_id=p.id AND pd.id=l.idbrg AND l.idsrj=s.id AND l.idcust>100 AND s.date>='".$data_t->tgl."' GROUP BY pd.produk_id) m_sk"), function($join) {
          $join->on('d_p.kd_prod', '=', 'm_sk.produk_id');
        })
        ->leftJoin(DB::raw("(select kd_prod, ramalan, safety_stock from ramal_$periode where dibuat='$tgl') rml"), function($join) {
          $join->on('d_p.kd_prod', '=', 'rml.kd_prod');
        })
        ->leftJoin(DB::raw("(select * from r_det_beli where kd_rbeli='$kd') rb"), function($join) {
          $join->on('d_p.kd_prod', '=', 'rb.kd_prod');
        })
        ->orderByRaw('d_p.kd_prod ASC')
        ->get();
    }else{
      $data = DB::table("d_prod as d_p")
        ->select(DB::raw("d_p.kd_prod, d_p.nm_db, d_p.satuan, (ifnull(m_sm.jumlah,0) - ifnull(m_sk.jumlah,0)) as jumlah, ifnull(m_sm.jumlah,0)as masuk, ifnull(m_sk.jumlah,0)as keluar, rml.ramalan as ramal, rml.safety_stock as safety, rb.jumlah as r_jml, rb.jml_disetujui as r_diset"))
        ->leftJoin(DB::raw("(SELECT pd.produk_id, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM pelumas.produk_diorder pd, pelumas.produk p, pelumas.logsrj l, pelumas.suratjalan s WHERE pd.id=l.idbrg AND l.idsrj=s.id AND pd.produk_id=p.id AND s.gudangasal<=1 AND s.date>='".$data_t->tgl."' GROUP BY pd.produk_id) m_sm"), function($join) {
          $join->on('d_p.kd_prod', '=', 'm_sm.produk_id');
        })
        ->leftJoin(DB::raw("(SELECT pd.produk_id, SUM(CASE WHEN p.dus>pd.satuan THEN pd.jumlah/p.dus ELSE pd.jumlah END) as jumlah FROM pelumas.produk_diorder pd, pelumas.produk p, pelumas.logsrj l, pelumas.suratjalan s WHERE pd.produk_id=p.id AND pd.id=l.idbrg AND l.idsrj=s.id AND l.idcust>100 AND s.date>='".$data_t->tgl."' GROUP BY pd.produk_id) m_sk"), function($join) {
          $join->on('d_p.kd_prod', '=', 'm_sk.produk_id');
        })
        ->leftJoin(DB::raw("(select kd_prod, ramalan, safety_stock from ramal_$periode where dibuat='$tgl') rml"), function($join) {
          $join->on('d_p.kd_prod', '=', 'rml.kd_prod');
        })
        ->join(DB::raw("(select * from r_det_beli where kd_rbeli='$kd') rb"), function($join) {
          $join->on('d_p.kd_prod', '=', 'rb.kd_prod');
        })
        ->orderByRaw('d_p.kd_prod ASC')
        ->get();
    }
    
    foreach( $data as $baris ){
      if(!is_null($baris->ramal)){ $baris->ramal = array_slice(json_decode($baris->ramal), -4); }
    }

    if($data_tg){ 
      $data[0]->dibuat = $data_tg->dibuat;
      $data[0]->r_awal = $data_tg->r_awal;
      $data[0]->r_ming = $periode=='ming' ? $data_tg->r_ming : '';
    }else{
      $data[0]->dibuat = '';
      $data[0]->r_awal = '';
      $data[0]->r_ming = '';
    }
    
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
      $data_t = DB::table("f_penj_bulan")
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
      $data_t = DB::table("f_penj_ming")
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
      $data_t = DB::table("f_penj_hari")
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
