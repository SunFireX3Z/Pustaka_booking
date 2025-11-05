<?php
namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\BukuKategoriModel;
use App\Models\KategoriModel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;

class Buku extends BaseController
{
    public function index()
    {
        $bukuModel = new BukuModel();
        $kategoriModel = new KategoriModel(); 
        
        // Ambil semua buku beserta kategori-nya
        $allBooks = $bukuModel->select('buku.*, 
            GROUP_CONCAT(kategori.nama_kategori SEPARATOR ", ") as kategori_nama, 
            GROUP_CONCAT(kategori.id_kategori SEPARATOR ",") as kategori_ids')
            ->join('buku_kategori', 'buku_kategori.buku_id = buku.id', 'left')
            ->join('kategori', 'kategori.id_kategori = buku_kategori.kategori_id', 'left')
            ->groupBy('buku.id')
            ->orderBy('buku.judul_buku', 'ASC')
            ->findAll();

        $data['buku'] = $allBooks;
        // Data kartu statistik
        $data['total_judul_buku'] = count($allBooks);
        $data['total_stok_buku'] = $bukuModel->selectSum('stok')->get()->getRow()->stok ?? 0;

        $data['kategori'] = $kategoriModel->findAll();
        $data['validation'] = \Config\Services::validation();

        return view('pages/admin/buku', $data);
    }
    
    public function create()
    {
        $bukuModel = new BukuModel();
        $bukuKategoriModel = new BukuKategoriModel();
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'judul_buku'   => 'required',
            'pengarang'    => 'required',
            'penerbit'     => 'required',
            'tahun_terbit' => 'required|integer',
            'isbn'         => 'required',
            'eisbn'        => 'permit_empty',
            'stok'         => 'required|integer|greater_than_equal_to[0]',
            'image'        => 'max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
            'deskripsi'    => 'required',
            'file_pdf'     => 'max_size[file_pdf,5120]|ext_in[file_pdf,pdf]', // Maks 5MB, hanya PDF
            'kategori_ids' => 'required' // Validasi untuk multi-select kategori
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('show_add_modal', true);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Inisialisasi nama file
        $imageName = 'default.jpg';
        $pdfName = null;

        // Handle upload gambar
        $imgFile = $this->request->getFile('image');
        if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
            $imageName = $imgFile->getRandomName();
            $imgFile->move(ROOTPATH . 'public/uploads', $imageName);
        }

        // Handle upload PDF (jika ada)
        $pdfFile = $this->request->getFile('file_pdf');
        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $pdfName = $pdfFile->getRandomName();
            $pdfFile->move(ROOTPATH . 'public/uploads/pdf', $pdfName);
        }

        // Siapkan data untuk disimpan
        $dataToSave = [
            'judul_buku'   => $this->request->getPost('judul_buku'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'isbn'         => $this->request->getPost('isbn'),
            'eisbn'        => $this->request->getPost('eisbn'),
            'stok'         => $this->request->getPost('stok'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'image'        => $imageName,
            'file_pdf'     => $pdfName,
        ];

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            $bukuModel->insert($dataToSave);
            $bukuId = $bukuModel->getInsertID();

            // Simpan kategori ke tabel pivot
            $kategoriIds = $this->request->getPost('kategori_ids');
            if (!empty($kategoriIds)) {
                foreach ($kategoriIds as $kategoriId) {
                    $bukuKategoriModel->insert([
                        'buku_id' => $bukuId,
                        'kategori_id' => $kategoriId
                    ]);
                }
            }
            $db->transComplete();
            session()->setFlashdata('success', 'Buku berhasil ditambahkan.'); 
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // Jika penyimpanan DB gagal, hapus file yang sudah terlanjur di-upload
            if ($imageName !== 'default.jpg' && file_exists(ROOTPATH . 'public/uploads/' . $imageName)) {
                unlink(ROOTPATH . 'public/uploads/' . $imageName);
            }
            if ($pdfName !== '' && file_exists(ROOTPATH . 'public/uploads/pdf/' . $pdfName)) {
                unlink(ROOTPATH . 'public/uploads/pdf/' . $pdfName);
            }
            session()->setFlashdata('error_upload', 'Gagal menyimpan data ke database. Pesan error: ' . $e->getMessage());
            session()->setFlashdata('show_add_modal', true);
            return redirect()->to('/buku')->withInput();
        }

        return redirect()->to('/buku');
    }

    public function update($id)
    {
        $bukuModel = new BukuModel();
        $bukuKategoriModel = new BukuKategoriModel();
        $validation = \Config\Services::validation();

        $rules = [
            'judul_buku'   => 'required',
            'pengarang'    => 'required',
            'penerbit'     => 'required',
            'tahun_terbit' => 'required|integer',
            'isbn'         => 'required',
            'eisbn'        => 'permit_empty',
            'stok'         => 'required|integer|greater_than_equal_to[0]',
            'deskripsi'    => 'required',
            'kategori_ids' => 'required',
            'image'        => 'max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
            'file_pdf'     => 'max_size[file_pdf,5120]|ext_in[file_pdf,pdf]' // Validasi untuk PDF
        ];

        if (!$this->validate($rules)) {
            // SOLUSI: Ambil data buku LENGKAP dengan kategori_ids untuk dikirim kembali jika validasi gagal
            $bookToEdit = $bukuModel->select('buku.*, GROUP_CONCAT(kategori.nama_kategori SEPARATOR ", ") as kategori_nama, GROUP_CONCAT(kategori.id_kategori SEPARATOR ",") as kategori_ids')
                                    ->join('buku_kategori', 'buku_kategori.buku_id = buku.id', 'left')
                                    ->join('kategori', 'kategori.id_kategori = buku_kategori.kategori_id', 'left')
                                    ->where('buku.id', $id)
                                    ->groupBy('buku.id')
                                    ->first();
            
            session()->setFlashdata('show_edit_modal', true);
            session()->setFlashdata('book_to_edit', $bookToEdit); // Kirim data buku yang sudah lengkap
            return redirect()->to('/buku')->withInput()->with('validation', $validation);
        }

        $oldBook = $bukuModel->find($id); // Ambil data buku lama untuk proses file
        $imageName = $oldBook['image'];
        $newPdfName = $oldBook['file_pdf'] ?? '';

        // Jika ada gambar baru yang diupload
        $imgFile = $this->request->getFile('image');
        if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
            $imageName = $imgFile->getRandomName();
            $imgFile->move(ROOTPATH . 'public/uploads', $imageName);

            // Hapus gambar lama jika bukan default.jpg
            if ($oldBook['image'] && $oldBook['image'] !== 'default.jpg' && file_exists(ROOTPATH . 'public/uploads/' . $oldBook['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $oldBook['image']);
            }
        }

        // Jika ada file PDF baru yang diupload
        $pdfFile = $this->request->getFile('file_pdf');
        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $newPdfName = $pdfFile->getRandomName();
            $pdfFile->move(ROOTPATH . 'public/uploads/pdf', $newPdfName);

            // Hapus file PDF lama jika ada
            if (!empty($oldBook['file_pdf']) && file_exists(ROOTPATH . 'public/uploads/pdf/' . $oldBook['file_pdf'])) {
                unlink(ROOTPATH . 'public/uploads/pdf/' . $oldBook['file_pdf']);
            }
        }

        $dataToSave = [
            'judul_buku'   => $this->request->getPost('judul_buku'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'isbn'         => $this->request->getPost('isbn'),
            'eisbn'        => $this->request->getPost('eisbn'),
            'stok'         => $this->request->getPost('stok'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'image'        => $imageName,
            'file_pdf'     => $newPdfName,
        ];

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Update data utama buku
            $bukuModel->update($id, $dataToSave);

            // 2. Hapus kategori lama dari tabel pivot
            $bukuKategoriModel->where('buku_id', $id)->delete();

            // 3. Masukkan kategori baru
            $kategoriIds = $this->request->getPost('kategori_ids');
            if (!empty($kategoriIds)) {
                foreach ($kategoriIds as $kategoriId) {
                    $bukuKategoriModel->insert(['buku_id' => $id, 'kategori_id' => $kategoriId]);
                }
            }
            $db->transComplete();
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

        // PERBAIKAN: Cek apakah buku sedang dipinjam atau dibooking
        if ($book && ($book['dipinjam'] > 0 || $book['dibooking'] > 0)) {
            session()->setFlashdata('error', 'Buku tidak dapat dihapus karena sedang dalam proses peminjaman atau booking.');
            return redirect()->to('/buku');
        }

        if ($book) {
            // Hapus gambar jika bukan default
            if ($book['image'] && $book['image'] !== 'default.jpg' && file_exists(ROOTPATH . 'public/uploads/' . $book['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $book['image']);
            }
            // Hapus file PDF jika ada
            if (!empty($book['file_pdf']) && file_exists(ROOTPATH . 'public/uploads/pdf/' . $book['file_pdf'])) {
                unlink(ROOTPATH . 'public/uploads/pdf/' . $book['file_pdf']);
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

    public function generateQrCode($id)
    {
        $bukuModel = new BukuModel();
        $buku = $bukuModel->find($id);

        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Buku tidak ditemukan.");
        }

        $qrData = (string)$id;

        // Perhatikan: kita instansiasi Builder dengan new Builder(...), lalu ->build()
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $qrData,
            size: 300,
            margin: 10
            // bisa tambah: labelText, logoPath, errorCorrectionLevel, dll.
        );

        $result = $builder->build();

        return $this->response
            ->setHeader('Content-Type', $result->getMimeType())
            ->setBody($result->getString());
    }
}
?>