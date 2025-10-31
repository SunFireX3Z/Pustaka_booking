<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function index()
    {
        $data = []; // Data untuk laporan akan ditambahkan di sini nanti
        return view('pages/admin/laporan', $data);
    }
}