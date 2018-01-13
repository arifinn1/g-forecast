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
    'berlaku', 'jam', 'dibuat_oleh'
  ];

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
