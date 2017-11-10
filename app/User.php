<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Hash;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'akun';
    protected $primaryKey = 'kd';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nik', 'nama', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function cek_nik($nik, $nik_l)
    {
        $data = DB::table('akun')
            ->select('nik')
            ->whereRaw("nik!='{$nik_l}' AND nik='{$nik}'")
            ->get();

        $jml = $data->count();
        
        return $jml==0 ? true: false;
    }

    public function cek_pass($nik, $pass)
    {
        $ret = false;
        $validasi = DB::table('akun')
            ->select('password')
            ->where('nik', $nik)
            ->first();

        if ($validasi && Hash::check($pass, $validasi->password)) { $ret = true; }
        return $ret;
    }
}
