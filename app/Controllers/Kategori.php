<?php
namespace App\Controllers;

use App\Models\KategoriModel;

class Kategori extends BaseController
{
    public function index()
    {
        $kategoriModel = new KategoriModel();

        // Query yang diperbaiki: Join dengan tabel pivot 'buku_kategori'
        $data['kategori'] = $kategoriModel
            ->select('kategori.*, COUNT(buku_kategori.buku_id) as jumlah_buku')
            ->join('buku_kategori', 'buku_kategori.kategori_id = kategori.id_kategori', 'left')
            ->groupBy('kategori.id_kategori')
            ->orderBy('kategori.nama_kategori', 'ASC')
            ->findAll();

        $data['total_kategori'] = count($data['kategori']);

        // Menentukan kategori terpopuler
        $kategori_populer = ['nama' => 'Belum ada', 'jumlah' => 0];
        if (!empty($data['kategori'])) {
            $kategori_populer['nama'] = $data['kategori'][0]['nama_kategori'];
            $kategori_populer['jumlah'] = $data['kategori'][0]['jumlah_buku'];
            foreach ($data['kategori'] as $k) {
                if ($k['jumlah_buku'] > $kategori_populer['jumlah']) {
                    $kategori_populer['nama'] = $k['nama_kategori'];
                    $kategori_populer['jumlah'] = $k['jumlah_buku'];
                }
            }
        }
        $data['kategori_populer'] = $kategori_populer;

        $data['validation'] = \Config\Services::validation();

        return view('pages/admin/kategori', $data);
    }

    public function create()
    {
        $kategoriModel = new KategoriModel();

        $rules = [
            'nama_kategori' => 'required|is_unique[kategori.nama_kategori]'
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembali ke halaman sebelumnya dengan error
            // dan data input agar modal bisa terbuka kembali dengan error.
            return redirect()->to('/kategori')->withInput()->with('validation', $this->validator);
        }

        // Simpan data jika validasi berhasil
        $kategoriModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);

        session()->setFlashdata('success', 'Kategori berhasil ditambahkan.');
        return redirect()->to('/kategori');
    }

    public function update($id)
    {
        $kategoriModel = new KategoriModel();

        $rules = [
            'nama_kategori' => "required|is_unique[kategori.nama_kategori,id_kategori,{$id}]"
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembali dengan error.
            return redirect()->to('/kategori')->withInput()->with('validation', $this->validator);
        }

        // Update data jika validasi berhasil
        $kategoriModel->save([
            'id_kategori'   => $id,
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);

        session()->setFlashdata('success', 'Kategori berhasil diperbarui.');
        return redirect()->to('/kategori');
    }
    public function delete($id)
    {
        $kategoriModel = new KategoriModel();
        if ($kategoriModel->isUsed($id)) {
            return redirect()->to('/kategori')->with('error', 'Gagal menghapus! Kategori ini masih digunakan oleh beberapa buku.');
        }
        $kategoriModel->delete($id);
        session()->setFlashdata('success', 'Kategori berhasil dihapus.');
        return redirect()->to('/kategori');
    }
}