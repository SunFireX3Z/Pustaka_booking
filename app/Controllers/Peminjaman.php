<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;

use App\Models\DendaModel;
use App\Models\DetailPeminjamanModel;
use App\Models\BukuModel;
use App\Models\WebProfileModel;

class Peminjaman extends BaseController
{
    public function index()
    {
        $peminjamanModel = new PeminjamanModel();
        $statusFilter = $this->request->getGet('status');

        // Mengambil data peminjaman beserta nama user dan jumlah buku yang dipinjam
        $query = $peminjamanModel
            ->select('peminjaman.*, user.nama as nama_user, COUNT(detail_peminjaman.id_buku) as jumlah_buku')
            ->join('user', 'user.id = peminjaman.id_user')
            ->join('detail_peminjaman', 'detail_peminjaman.id_pinjam = peminjaman.id_pinjam', 'left')
            ->groupBy('peminjaman.id_pinjam')
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC');

        // Terapkan filter status jika ada
        if ($statusFilter && $statusFilter !== 'semua') {
            if ($statusFilter === 'terlambat') {
                $query->where('peminjaman.status', 'dipinjam')
                      ->where('peminjaman.tanggal_kembali <', date('Y-m-d'));
            } elseif ($statusFilter === 'dipinjam') {
                $query->where('peminjaman.status', 'dipinjam')
                      ->where('peminjaman.tanggal_kembali >=', date('Y-m-d'));
            } else {
                $query->where('peminjaman.status', $statusFilter);
            }
        }

        // Paginasi data
        $data = [
            'peminjaman' => $query->paginate(10, 'peminjaman'), // 10 item per halaman
            'pager' => $peminjamanModel->pager,
            'currentPage' => $this->request->getVar('page_peminjaman') ? $this->request->getVar('page_peminjaman') : 1,
            'perPage' => 10,
            'selected_status' => $statusFilter,
            'current_page' => 'peminjaman',
        ];

        return view('pages/admin/peminjaman', $data);
    }

    public function returnBook($peminjamanId)
    {
        // Load model yang diperlukan
        $peminjamanModel = new PeminjamanModel();
        $detailPeminjamanModel = new DetailPeminjamanModel();
        $bukuModel = new BukuModel();
        $dendaModel = new DendaModel();
        $webProfileModel = new WebProfileModel();

        // 1. Validasi peminjaman
        $peminjaman = $peminjamanModel->find($peminjamanId);
        if (!$peminjaman || $peminjaman['status'] !== 'dipinjam') {
            return redirect()->to('/peminjaman')->with('error', 'Transaksi peminjaman tidak valid atau sudah dikembalikan.');
        }

        // Ambil pengaturan dari profil web
        $profile = $webProfileModel->find(1) ?? ['denda_per_hari' => 1000];

        // 2. Hitung denda jika terlambat
        $dendaPerHari = $profile['denda_per_hari'];
        $totalDenda = 0;
        $tanggalKembali = new \DateTime($peminjaman['tanggal_kembali']);
        $tanggalDikembalikan = new \DateTime(date('Y-m-d'));
        
        if ($tanggalDikembalikan > $tanggalKembali) {
            $selisihHari = $tanggalDikembalikan->diff($tanggalKembali)->days;
            $totalDenda = $selisihHari * $dendaPerHari;
        }

        // 3. Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 4. Update status peminjaman
            $peminjamanModel->update($peminjamanId, [
                'status' => 'kembali',
                'tanggal_dikembalikan' => date('Y-m-d'),
                'total_denda' => $totalDenda
            ]);

            // 5. Kembalikan stok buku
            $borrowedBooks = $detailPeminjamanModel->where('id_pinjam', $peminjamanId)->findAll();
            foreach ($borrowedBooks as $detail) {
                // Validasi tambahan: pastikan buku ada sebelum update
                $buku = $bukuModel->find($detail['id_buku']);
                if ($buku) {
                    $bukuModel->set('stok', 'stok + 1', false)
                              ->set('dipinjam', 'dipinjam - 1', false)
                              ->where('id', $detail['id_buku'])->update();
                }
            }

            // 6. Jika ada denda, buat entri baru di tabel denda
            if ($totalDenda > 0) {
                $dendaModel->insert([
                    'id_user' => $peminjaman['id_user'],
                    'id_pinjam' => $peminjamanId,
                    'jumlah_denda' => $totalDenda,
                ]);
            }

            $db->transComplete();
            $successMessage = 'Buku berhasil dikembalikan.';
            if ($totalDenda > 0) {
                $successMessage .= ' Denda keterlambatan sebesar Rp ' . number_format($totalDenda, 0, ',', '.');
            }
            return redirect()->to('/peminjaman')->with('success', $successMessage);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/peminjaman')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getDetail($peminjamanId)
    {
        $detailPeminjamanModel = new DetailPeminjamanModel();
        $details = $detailPeminjamanModel
            ->select('buku.judul_buku')
            ->join('buku', 'buku.id = detail_peminjaman.id_buku')
            ->where('id_pinjam', $peminjamanId)
            ->findAll();

        return $this->response->setJSON($details);
    }
}