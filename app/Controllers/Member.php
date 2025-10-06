<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Member extends BaseController
{
    public function index()
    {
        $bukuModel = new BukuModel();
 
        // Data yang akan dikirim ke view
        $data = [
            'title' => 'Homepage Member',
            'nama_member' => session()->get('nama'),
            // Ambil 4 buku terbaru berdasarkan id
            'buku_terbaru' => $bukuModel->select('buku.*, kategori.nama_kategori')
                                        ->join('kategori', 'kategori.id_kategori = buku.id_kategori', 'left')
                                        ->orderBy('buku.id', 'DESC')
                                        ->limit(12)
                                        ->findAll(),
            // Data ini masih statis, nantinya bisa diisi dari database
            'jumlah_dipinjam' => 0, // Contoh statis
            'jumlah_dibooking' => 0, // Contoh statis
        ];
 
        // Mengirim data ke view
        return view('pages/member/homepage', $data);
    }
}