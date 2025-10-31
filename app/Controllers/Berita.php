<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\KategoriBeritaModel;

class Berita extends BaseController
{
    protected $beritaModel;
    protected $kategoriBeritaModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
        $this->kategoriBeritaModel = new KategoriBeritaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Berita',
            'berita' => $this->beritaModel
                ->select('berita.*, kategori_berita.nama_kategori')
                ->join('kategori_berita', 'kategori_berita.id = berita.id_kategori_berita')
                ->orderBy('berita.created_at', 'DESC')
                ->findAll(),
            'kategori_berita' => $this->kategoriBeritaModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('pages/admin/berita', $data);
    }

    public function create()
    {
        $validation = $this->validate([
            'judul' => 'required',
            'id_kategori_berita' => 'required',
            'isi_berita' => 'required',
            'thumbnail' => 'uploaded[thumbnail]|max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]'
        ]);

        if (!$validation) {
            return redirect()->to('/berita')->withInput();
        }

        $thumbnailFile = $this->request->getFile('thumbnail');
        $thumbnailName = $thumbnailFile->getRandomName();
        $thumbnailFile->move('uploads/berita', $thumbnailName);

        $this->beritaModel->save([
            'judul' => $this->request->getPost('judul'),
            'id_kategori_berita' => $this->request->getPost('id_kategori_berita'),
            'isi_berita' => $this->request->getPost('isi_berita'),
            'thumbnail' => $thumbnailName,
            'status' => $this->request->getPost('status') ? 'published' : 'draft'
        ]);

        session()->setFlashdata('success', 'Berita berhasil ditambahkan.');
        return redirect()->to('/berita');
    }

    public function update($id)
    {
        $validation = $this->validate([
            'judul' => 'required',
            'id_kategori_berita' => 'required',
            'isi_berita' => 'required',
            'thumbnail' => 'max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]'
        ]);

        if (!$validation) {
            return redirect()->to('/berita')->withInput();
        }

        $berita = $this->beritaModel->find($id);
        $thumbnailFile = $this->request->getFile('thumbnail');

        if ($thumbnailFile->getError() == 4) { // Tidak ada file diupload
            $thumbnailName = $berita['thumbnail'];
        } else {
            $thumbnailName = $thumbnailFile->getRandomName();
            $thumbnailFile->move('uploads/berita', $thumbnailName);
            // Hapus file lama jika ada
            if ($berita['thumbnail'] && file_exists('uploads/berita/' . $berita['thumbnail'])) {
                unlink('uploads/berita/' . $berita['thumbnail']);
            }
        }

        $this->beritaModel->save([
            'id' => $id,
            'judul' => $this->request->getPost('judul'),
            'id_kategori_berita' => $this->request->getPost('id_kategori_berita'),
            'isi_berita' => $this->request->getPost('isi_berita'),
            'thumbnail' => $thumbnailName,
            'status' => $this->request->getPost('status') ? 'published' : 'draft'
        ]);

        session()->setFlashdata('success', 'Berita berhasil diperbarui.');
        return redirect()->to('/berita');
    }

    public function delete($id)
    {
        $berita = $this->beritaModel->find($id);

        if (!$berita) {
            session()->setFlashdata('error', 'Berita tidak ditemukan.');
            return redirect()->to('/berita');
        }

        // Hapus file thumbnail
        if ($berita['thumbnail'] && file_exists('uploads/berita/' . $berita['thumbnail'])) {
            unlink('uploads/berita/' . $berita['thumbnail']);
        }

        $this->beritaModel->delete($id);

        session()->setFlashdata('success', 'Berita berhasil dihapus.');
        return redirect()->to('/berita');
    }

    public function toggleStatus($id)
    {
        if ($this->request->isAJAX()) {
            $berita = $this->beritaModel->find($id);
            if ($berita) {
                $newStatus = ($berita['status'] === 'published') ? 'draft' : 'published';
                $this->beritaModel->update($id, ['status' => $newStatus]);
                return $this->response->setJSON(['success' => true, 'status' => $newStatus]);
            }
            return $this->response->setJSON(['success' => false, 'message' => 'Berita tidak ditemukan.']);
        }
        // Jika bukan AJAX, tolak akses
        return $this->response->setStatusCode(403);
    }
}