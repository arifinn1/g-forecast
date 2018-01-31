<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->session()->exists('g_nik')) {
            $user = Auth::user();
            $request->session()->put('g_kd', $user->kd);
            $request->session()->put('g_nik', $user->nik);
            $request->session()->put('g_nama', $user->nama);
            $request->session()->put('g_posisi', $user->posisi);
        }

        $data = [];
        $data['title'] = 'Home - Genetic Forecast';
        $data['nama'] = $request->session()->get('g_nama');
        $data['posisi'] = $request->session()->get('g_posisi');

        return view('home', $data);
    }
}
