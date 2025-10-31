<?php

namespace App\Controllers;

class Denda extends BaseController
{
    public function index()
    {
        $data = []; // Data denda akan ditambahkan di sini nanti
        return view('pages/admin/denda', $data);
    }
}