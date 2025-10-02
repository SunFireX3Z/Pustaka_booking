<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\BukuModel;

class Kategori extends BaseController
{
    protected $kategoriModel;
    protected $bukuModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->bukuModel = new BukuModel();
    }

    public function index()
    {
        // Data untuk kartu statistik
        $data['total_kategori'] = $this->kategoriModel->countAllResults();
        $data['total_judul_buku'] = $this->bukuModel->countAllResults();

        // Menemukan kategori terpopuler (berdasarkan jumlah judul buku)
        $kategoriPopuler = $this->kategoriModel
            ->select('kategori.nama_kategori as nama, COUNT(buku.id) as jumlah_buku')
            ->join('buku', 'buku.id_kategori = kategori.id_kategori', 'left')
            ->groupBy('kategori.id_kategori, kategori.nama_kategori')
            ->orderBy('jumlah_buku', 'DESC')
            ->first();
        $data['kategori_populer'] = $kategoriPopuler ?? ['nama' => 'Belum ada data'];

        // Mengambil semua kategori beserta jumlah buku dan cover acak
        $kategoriList = $this->kategoriModel
            ->select('kategori.*, COUNT(buku.id) as jumlah_buku')
            ->join('buku', 'buku.id_kategori = kategori.id_kategori', 'left')
            ->groupBy('kategori.id_kategori')
            ->orderBy('kategori.nama_kategori', 'ASC')
            ->findAll();

        // Menambahkan cover acak untuk setiap kategori yang memiliki buku
        foreach ($kategoriList as &$k) {
            if ($k['jumlah_buku'] > 0) {
                $randomBook = $this->bukuModel->where('id_kategori', $k['id_kategori'])->orderBy('RAND()')->first();
                $k['random_cover'] = $randomBook['image'] ?? 'default.png';
            } else {
                $k['random_cover'] = 'default.png';
            }
        }
        $data['kategori'] = $kategoriList;

        return view('pages/admin/kategori', $data);
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules(['nama_kategori' => 'required|is_unique[kategori.nama_kategori]']);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/kategori')->withInput()->with('validation', $validation)->with('show_modal', 'add');
        }

        $this->kategoriModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);

        return redirect()->to('/kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules(['nama_kategori' => "required|is_unique[kategori.nama_kategori,id_kategori,{$id}]"]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/kategori')->withInput()->with('validation', $validation)->with('show_modal', 'edit')->with('edit_id', $id);
        }

        $this->kategoriModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);

        return redirect()->to('/kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Cek apakah ada buku yang menggunakan kategori ini
        $bukuTerkait = $this->bukuModel->where('id_kategori', $id)->countAllResults();
        if ($bukuTerkait > 0) {
            return redirect()->to('/kategori')->with('error', 'Gagal menghapus! Kategori ini masih digunakan oleh ' . $bukuTerkait . ' buku.');
        }

        $this->kategoriModel->delete($id);
        return redirect()->to('/kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}