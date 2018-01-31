<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jadwal extends Model
{
  protected $table = 'jadwal';
  protected $primaryKey = 'kd';
  public $timestamps = false;

  protected $fillable = [
    'berlaku', 'jam', 'up_terakhir', 'dibuat_oleh'
  ];

  public function ambil_up_terakhir()
  {
    $data = DB::table('jadwal')
      ->select(DB::raw('kd, ifnull( up_terakhir, ifnull((select max(tgl) from f_penj_hari), (select min(date) from pelumas.suratjalan)) ) as up_terakhir, (SELECT COUNT(DISTINCT(tgl)) FROM f_penj_hari) as hari, (SELECT COUNT(DISTINCT(CONCAT(tahun,minggu))) FROM f_penj_ming) as minggu, (SELECT COUNT(DISTINCT(CONCAT(tahun,bulan))) FROM f_penj_bulan) as bulan'))
      ->whereRaw('berlaku<=now()')
      ->orderBy('berlaku', 'DESC')
      ->first();
    
    return $data;
  }

  public function update_up_terakhir($kd)
  {
    $data = DB::table('f_penj_hari')
      ->select(DB::raw('max(tgl) as tgl'))
      ->first();
    
    DB::table('jadwal')->where('kd', $kd)->update(['up_terakhir' => $data->tgl]);
  }

  public function tampil_jadwal()
  {
    $data = DB::table('jadwal as j')
      ->join('akun as a', 'a.kd', '=', 'j.dibuat_oleh')
      ->select('j.*', 'a.nama')
      ->get();
    
    return $data;
  }

  public function tampil_jadwal_by($kd)
  {
    $data = DB::table('jadwal as j')
      ->join('akun as a', 'a.kd', '=', 'j.dibuat_oleh')
      ->select('j.*', 'a.nama')
      ->where('j.kd', $kd)
      ->first();
    
    return $data;
  }

  public function tampil_jadwal_last()
  {
    $data = DB::table('jadwal as j')
      ->join('akun as a', 'a.kd', '=', 'j.dibuat_oleh')
      ->select('j.*', 'a.nama')
      ->orderByRaw('j.kd DESC')
      ->first();
    
    return $data;
  }
}
