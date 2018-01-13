<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Safety;

class SafetyController extends Controller
{
    public function index(Request $request)
    {
        $safety = new Safety();
        $data = [];
        $data['datatables'] = true;
        $data['title'] = 'Safety Stock - Genetic Forecast';
        $data['nama'] = $request->session()->get('nama');

        return view('safety/lihat', $data);
    }
}
