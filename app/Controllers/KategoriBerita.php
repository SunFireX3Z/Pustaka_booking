<?php

namespace App\Controllers;

use App\Models\KategoriBeritaModel;
use App\Models\BeritaModel;

class KategoriBerita extends BaseController
{
    protected $kategoriBeritaModel;
    protected $beritaModel;

    public function __construct()
    {
        $this->kategoriBeritaModel = new KategoriBeritaModel();
        $this->beritaModel = new BeritaModel();
    }

    public function index()
    {
        $kategoriList = $this->kategoriBeritaModel
            ->select('kategori_berita.*, COUNT(berita.id) as jumlah_berita')
            ->join('berita', 'berita.id_kategori_berita = kategori_berita.id', 'left')
            ->groupBy('kategori_berita.id')
            ->orderBy('kategori_berita.nama_kategori', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Kategori Berita',
            'kategori_berita' => $kategoriList,
            'validation' => \Config\Services::validation()
        ];

        return view('pages/admin/kategori_berita', $data);
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules(['nama_kategori' => 'required|is_unique[kategori_berita.nama_kategori]']);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/kategori-berita')->withInput()->with('validation', $validation)->with('show_modal', 'add');
        }

        $this->kategoriBeritaModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ]);

        return redirect()->to('/kategori-berita')->with('success', 'Kategori berita berhasil ditambahkan.');
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules(['nama_kategori' => "required|is_unique[kategori_berita.nama_kategori,id,{$id}]"]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/kategori-berita')->withInput()->with('validation', $validation)->with('show_modal', 'edit')->with('edit_id', $id);
        }

        $this->kategoriBeritaModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);

        return redirect()->to('/kategori-berita')->with('success', 'Kategori berita berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Cek apakah ada berita yang menggunakan kategori ini
        $beritaTerkait = $this->beritaModel->where('id_kategori_berita', $id)->countAllResults();
        if ($beritaTerkait > 0) {
            return redirect()->to('/kategori-berita')->with('error', 'Gagal menghapus! Kategori ini masih digunakan oleh ' . $beritaTerkait . ' berita.');
        }

        $this->kategoriBeritaModel->delete($id);
        return redirect()->to('/kategori-berita')->with('success', 'Kategori berita berhasil dihapus.');
    }
}