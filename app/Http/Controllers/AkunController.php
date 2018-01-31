<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;

class AkunController extends Controller
{
  public function index(Request $request)
  {
    $akun = new User();
    $data = [];
    $data['akun'] = $akun->all();
    $data['datatables'] = true;
    $data['title'] = 'Akun - Genetic Forecast';
    $data['nama'] = $request->session()->get('g_nama');
    $data['posisi'] = $request->session()->get('g_posisi');

    return view('akun/lihat', $data);
  }

  public function simpan(Request $request, User $akun)
  {
    $ret = "";
    $nik = true;
    if($request->input('nik')!=$request->input('nik_l')){
      $nik = $akun->cek_nik($request->input('nik'), $request->input('nik_l'));
    }

    $data = [];
    $data['nik'] = $request->input('nik');
    $data['nama'] = $request->input('nama');
    $data['posisi'] = $request->input('posisi');

    if($nik){
      if($request->input('kd')==''){
        $data['password'] = bcrypt($request->input('password')=='' ? '123456' : $request->input('password'));
        $akun->insert($data);

        $ret = "BARU|".json_encode($akun->where('nik', $data['nik'])->first());
      }else{
        $data2 = $akun->find($request->input('kd'));
        $data2->nik = $data['nik'];
        $data2->nama = $data['nama'];
        $data2->posisi = $data['posisi'];
        $data2->save();
        $ret = "UBAH|".json_encode($akun->where('nik', $data['nik'])->first());
      }
    }else{ $ret = "NIK|[]"; }
    
    echo $ret;
  }

  public function hapus(Request $request, User $akun){
    $jml = $akun->find($request->input('kd'))->delete();
    echo $jml;
  }

  public function ganti_pass(Request $request)
  {
    $data = [];
    $data['title'] = 'Ganti Password - Genetic Forecast';
    $data['nama'] = $request->session()->get('g_nama');
    $data['posisi'] = $request->session()->get('g_posisi');
    return view('akun/ganti_pass', $data);
  }

  public function proses_ganti_pass(Request $request, User $akun)
  {
    $ret = "TEST";

    if($akun->cek_pass($request->session()->get('g_nik'), $request->input('pass_lama')))
    {
      $data = $akun->where('nik', $request->session()->get('g_nik'))->first();
      $data->password = bcrypt($request->input('pass_baru1'));
      $data->save();
      $ret = "SUKSES";
    }else{ $ret = "GAGAL"; }

    echo $ret;
  }
}
