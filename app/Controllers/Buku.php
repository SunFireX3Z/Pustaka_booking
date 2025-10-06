<?php
namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\KategoriModel;

class Buku extends BaseController
{
    public function index()
    {
        $bukuModel = new BukuModel();
        $kategoriModel = new KategoriModel(); 
        
        $allBooks = $bukuModel->select('buku.*, kategori.nama_kategori')
                              ->join('kategori', 'kategori.id_kategori = buku.id_kategori', 'left')
                              ->orderBy('kategori.nama_kategori', 'ASC') // Urutkan berdasarkan kategori
                              ->orderBy('buku.judul_buku', 'ASC')      // Lalu berdasarkan judul
                              ->findAll();

        // Kelompokkan buku berdasarkan kategori
        $groupedBooks = [];
        foreach ($allBooks as $book) {
            $categoryName = $book['nama_kategori'] ?? 'Tanpa Kategori';
            $groupedBooks[$categoryName][] = $book;
        }

        // Data untuk kartu statistik
        $data['total_judul_buku'] = count($allBooks);
        $data['total_stok_buku'] = $bukuModel->selectSum('stok')->get()->getRow()->stok ?? 0;

        $data['groupedBooks'] = $groupedBooks;
        $data['kategori'] = $kategoriModel->findAll();
        $data['validation'] = \Config\Services::validation();
        // Kirim data buku asli untuk modal edit
        $data['buku'] = $allBooks;
 
        return view('pages/admin/buku', $data);
    }
    
    public function create()
    {
        $bukuModel = new BukuModel();
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'judul_buku'   => 'required',
            'id_kategori'  => 'required',
            'pengarang'    => 'required',
            'penerbit'     => 'required',
            'tahun_terbit' => 'required|integer',
            'isbn'         => 'required',
            'stok'         => 'required|integer',
            'image'        => 'uploaded[image]|max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
            'deskripsi'    => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('show_add_modal', true);
            return redirect()->to('/buku')->withInput()->with('validation', $validation);
        }

        // Handle file upload
        $imgFile = $this->request->getFile('image');
        $newName = ''; // Inisialisasi variabel
        if ($imgFile->isValid() && !$imgFile->hasMoved()) {
            $newName = $imgFile->getRandomName();
            $imgFile->move(ROOTPATH . 'public/uploads', $newName);
        } else {
            // Jika upload gagal, kembali dengan error
            $error = $imgFile ? $imgFile->getErrorString() . '(' . $imgFile->getError() . ')' : 'File tidak valid atau tidak dipilih.';
            session()->setFlashdata('error_upload', 'Gagal mengupload gambar: ' . $error);
            session()->setFlashdata('show_add_modal', true);
            return redirect()->to('/buku')->withInput();
        }

        $dataToSave = [
            'judul_buku'   => $this->request->getPost('judul_buku'),
            'id_kategori'  => $this->request->getPost('id_kategori'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'isbn'         => $this->request->getPost('isbn'),
            'stok'         => $this->request->getPost('stok'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'image'        => $newName,
        ];

        try {
            $bukuModel->save($dataToSave);
            session()->setFlashdata('success', 'Buku berhasil ditambahkan.');
            return redirect()->to('/buku');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            if ($newName !== '' && file_exists(ROOTPATH . 'public/uploads/' . $newName)) {
                unlink(ROOTPATH . 'public/uploads/' . $newName);
            }
            session()->setFlashdata('error_upload', 'Gagal menyimpan data ke database. Pesan error: ' . $e->getMessage());
            session()->setFlashdata('show_add_modal', true);
            return redirect()->to('/buku')->withInput();
        }
    }

    public function update($id)
    {
        $bukuModel = new BukuModel();
        $validation = \Config\Services::validation();

        $rules = [
            'judul_buku'   => 'required',
            'id_kategori'  => 'required',
            'pengarang'    => 'required',
            'penerbit'     => 'required',
            'tahun_terbit' => 'required|integer',
            'isbn'         => 'required',
            'stok'         => 'required|integer',
            'deskripsi'    => 'required',
            'image'        => 'max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('show_edit_modal', $id);
            return redirect()->to('/buku')->withInput()->with('validation', $validation);
        }

        $oldBook = $bukuModel->find($id);
        $imgFile = $this->request->getFile('image');
        $newName = $oldBook['image'];

        // Jika ada gambar baru yang diupload
        if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
            $newName = $imgFile->getRandomName();
            $imgFile->move(ROOTPATH . 'public/uploads', $newName);

            // Hapus gambar lama jika bukan default
            if ($oldBook['image'] !== 'default.png' && file_exists(ROOTPATH . 'public/uploads/' . $oldBook['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $oldBook['image']);
            }
        }

        $dataToSave = [
            'judul_buku'   => $this->request->getPost('judul_buku'),
            'id_kategori'  => $this->request->getPost('id_kategori'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'isbn'         => $this->request->getPost('isbn'),
            'stok'         => $this->request->getPost('stok'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'image'        => $newName,
        ];

        try {
            $bukuModel->update($id, $dataToSave);
            session()->setFlashdata('success', 'Buku berhasil diperbarui.');
            return redirect()->to('/buku');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            session()->setFlashdata('error_upload', 'Gagal memperbarui data. Pesan error: ' . $e->getMessage());
            session()->setFlashdata('show_edit_modal', $id);
            return redirect()->to('/buku')->withInput();
        }
    }

    public function delete($id)
    {
        $bukuModel = new BukuModel();
        $book = $bukuModel->find($id);

        if ($book) {
            // Hapus gambar jika bukan default
            if ($book['image'] !== 'default.png' && file_exists(ROOTPATH . 'public/uploads/' . $book['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $book['image']);
            }
            $bukuModel->delete($id);
            session()->setFlashdata('success', 'Buku berhasil dihapus.');
        }
        return redirect()->to('/buku');
    }
    
    public function detail($id)
    {
        $bukuModel = new \App\Models\BukuModel();
        $buku = $bukuModel->find($id);
 
        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Buku dengan ID $id tidak ditemukan");
        }
 
        return view('buku/detail', ['buku' => $buku]);
    }
}
?>