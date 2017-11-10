<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jadwal;

class JadwalController extends Controller
{
    //
    public function index(Request $request)
    {
        $jadwal = new Jadwal();
        $data = [];
        $data['jadwal'] = $jadwal->tampil_jadwal();
        $data['datatables'] = true;
        $data['datetimepicker'] = true;
        $data['title'] = 'Jadwal - Genetic Forecast';
        $data['nama'] = $request->session()->get('nama');

        return view('jadwal/lihat', $data);
    }

    public function simpan(Request $request, Jadwal $jadwal)
    {
        $ret = "";
        $data = [];
        $data['berlaku'] =  date("Y-m-d", strtotime($request->input('berlaku')));
        $data['jam'] =  $request->input('jam').":00";
        $data['dibuat_oleh'] = $request->session()->get('kd');
        
        if($request->input('kd')==''){
            $jadwal->insert($data);

            $ret = "BARU|".json_encode($jadwal->tampil_jadwal_last());
        }else{
            $data2 = $jadwal->find($request->input('kd'));
            $data2->berlaku = $data['berlaku'];
            $data2->jam = $data['jam'];
            $data2->dibuat_oleh = $data['dibuat_oleh'];
            $data2->save();
            $ret = "UBAH|".json_encode($jadwal->tampil_jadwal_by($request->input('kd')));
        }
        
        echo $ret;
    }

    public function hapus(Request $request, Jadwal $jadwal){
        $jml = $jadwal->find($request->input('kd'))->delete();
        echo $jml;
    }
}
