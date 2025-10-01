<?php
namespace App\Controllers;

use App\Models\KategoriModel;

class Kategori extends BaseController
{
    public function index()
    {
        $kategoriModel = new KategoriModel();
        $data['kategori'] = $kategoriModel->orderBy('id_kategori', 'DESC')->findAll();

        return view('kategori', $data);
    }

    public function new()
    {
        helper('form');
        $data['validation'] = \Config\Services::validation();
        return view('kategori_form', $data);
    }

    public function create()
    {
        helper('form');
        $validation = \Config\Services::validation();

        $rules = [
            'nama_kategori' => 'required|min_length[3]|is_unique[kategori.nama_kategori]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/kategori/new')->withInput()->with('validation', $validation);
        }

        $kategoriModel = new KategoriModel();
        $kategoriModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ]);

        session()->setFlashdata('success', 'Kategori baru berhasil ditambahkan.');
        return redirect()->to('/kategori');
    }

    public function edit($id)
    {
        helper('form');
        $kategoriModel = new KategoriModel();
        $data['kategori'] = $kategoriModel->find($id);
        $data['validation'] = \Config\Services::validation();

        if (empty($data['kategori'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan: ' . $id);
        }

        return view('kategori_form', $data);
    }

    public function update($id)
    {
        $kategoriModel = new KategoriModel();
        $rules = [
            'nama_kategori' => "required|min_length[3]|is_unique[kategori.nama_kategori,id_kategori,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/kategori/edit/' . $id)->withInput()->with('validation', $this->validator);
        }

        $kategoriModel->update($id, ['nama_kategori' => $this->request->getPost('nama_kategori')]);
        session()->setFlashdata('success', 'Data kategori berhasil diperbarui.');
        return redirect()->to('/kategori');
    }

    public function delete($id)
    {
        $kategoriModel = new KategoriModel();
        // Tambahkan pengecekan jika kategori digunakan oleh buku sebelum menghapus
        $kategoriModel->delete($id);
        session()->setFlashdata('success', 'Kategori berhasil dihapus.');
        return redirect()->to('/kategori');
    }
}