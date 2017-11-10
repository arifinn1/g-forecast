<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RamalBulan;

class RamalBulanController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data['chart'] = true;
        $data['title'] = 'Ramal Bulan - Genetic Forecast';
        $data['nama'] = $request->session()->get('nama');

        return view('ramal/bulan', $data);
    }
}
