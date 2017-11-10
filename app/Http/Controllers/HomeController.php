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
        if (!$request->session()->exists('nik')) {
            $user = Auth::user();
            $request->session()->put('kd', $user->kd);
            $request->session()->put('nik', $user->nik);
            $request->session()->put('nama', $user->nama);
        }

        $data = [];
        $data['title'] = 'Home - Genetic Forecast';
        $data['nama'] = $request->session()->get('nama');

        return view('home', $data);
    }
}
