<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\PeminjamanModel;
use App\Models\BeritaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $bukuModel = new BukuModel();
        // Inisialisasi model yang dibutuhkan
        $bookingModel = new BookingModel();
        $pinjamModel = new PeminjamanModel();
        $beritaModel = new BeritaModel();

        // Data untuk card statistik
        $data['jumlahAnggota'] = $userModel->countAllResults();
        $data['stokBuku'] = $bukuModel->selectSum('stok')->first()['stok'] ?? 0;
        $data['dipinjam'] = $bukuModel->selectSum('dipinjam')->first()['dipinjam'] ?? 0;
        $data['dibooking'] = $bukuModel->selectSum('dibooking')->first()['dibooking'] ?? 0;

        // Data untuk tabel anggota & buku terbaru
        $data['users'] = $userModel->orderBy('tanggal_input', 'DESC')->limit(6)->find();
        // PERBAIKAN: Query buku disamakan dengan di Buku.php untuk mendapatkan kategori
        $data['buku'] = $bukuModel->select('buku.*, GROUP_CONCAT(kategori.nama_kategori SEPARATOR ", ") as kategori_nama')
            ->join('buku_kategori', 'buku_kategori.buku_id = buku.id', 'left')
            ->join('kategori', 'kategori.id_kategori = buku_kategori.kategori_id', 'left')
            ->groupBy('buku.id')
            ->orderBy('buku.id', 'DESC')
            ->limit(4)->find();

        // Data untuk Grafik Tren Anggota Baru (Dinamis)
        $memberTrendData = $this->getMemberTrend();
        $data['memberTrendLabels'] = json_encode($memberTrendData['labels']);
        $data['memberTrendCounts'] = json_encode($memberTrendData['counts']);

        // =================================================================
        // TAMBAHAN: Mengambil data untuk tabel-tabel baru di dashboard
        // =================================================================

        // 5. Data untuk Tabel Booking Terbaru
        $data['bookings_terbaru'] = $bookingModel
            ->select('booking.tanggal_booking as tgl_booking, booking.status, user.nama as nama_anggota, GROUP_CONCAT(buku.judul_buku SEPARATOR ", ") as judul_buku')
            ->join('user', 'user.id = booking.id_user')
            ->join('detail_booking', 'detail_booking.id_booking = booking.id_booking', 'left')
            ->join('buku', 'buku.id = detail_booking.id_buku', 'left')
            ->where('booking.status', 'dibooking') // HANYA tampilkan booking yang sudah diselesaikan member
            ->groupBy('booking.id_booking')
            ->orderBy('booking.tanggal_booking', 'DESC')
            ->limit(5)
            ->find();

        // 6. Data untuk Tabel Peminjaman Terbaru
        $data['peminjaman_terbaru'] = $pinjamModel
            ->select('peminjaman.tanggal_pinjam as tgl_pinjam, user.nama as nama_peminjam, GROUP_CONCAT(buku.judul_buku SEPARATOR ", ") as judul_buku')
            ->join('user', 'user.id = peminjaman.id_user')
            ->join('detail_peminjaman', 'detail_peminjaman.id_pinjam = peminjaman.id_pinjam', 'left')
            ->join('buku', 'buku.id = detail_peminjaman.id_buku', 'left')
            ->groupBy('peminjaman.id_pinjam')
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC')
            ->limit(5)
            ->find();

        // 7. Data untuk Tabel Berita Terbaru
        // Pastikan nama kolom foreign key di join sudah benar (contoh: berita.id_kategori_berita)
        $data['berita_terbaru'] = $beritaModel
            ->select('berita.judul, berita.thumbnail, kategori_berita.nama_kategori as kategori, berita.created_at as tanggal_publikasi')
            ->join('kategori_berita', 'kategori_berita.id = berita.id_kategori_berita', 'left')
            ->where('berita.status', 'published')
            ->orderBy('berita.created_at', 'DESC')
            ->limit(5)
            ->find();

        // 8. Data untuk Anggota yang Sedang Aktif (Online)
        $data['anggota_aktif'] = $this->getActiveUsers();

        return view('pages/admin/dashboard', $data);
    }

    /**
     * Mengambil data tren pendaftaran anggota untuk 6 bulan terakhir.
     *
     * @return array
     */
    private function getMemberTrend(): array
    {
        $userModel = new UserModel();
        $monthlyData = [];

        // Inisialisasi 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthlyData[$month] = 0;
        }

        // Ambil data dari database
        $sixMonthsAgo = date('Y-m-01', strtotime('-5 months'));
        $result = $userModel
            ->select("COUNT(id) as count, DATE_FORMAT(tanggal_input, '%Y-%m') as month")
            ->where('tanggal_input >=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->findAll();

        // Isi data dari hasil query
        foreach ($result as $row) {
            if (isset($monthlyData[$row['month']])) {
                $monthlyData[$row['month']] = (int) $row['count'];
            }
        }

        // Format nama bulan untuk label chart
        $labels = array_map(fn($m) => date('M Y', strtotime($m . '-01')), array_keys($monthlyData));

        return [
            'labels' => $labels,
            'counts' => array_values($monthlyData),
        ];
    }

    /**
     * Mengambil daftar pengguna yang sedang aktif (sesi di database).
     *
     * @return array
     */
    private function getActiveUsers(): array
    {
        $db = \Config\Database::connect();
        $sessionConfig = config('Session');
        $sessionTable = $sessionConfig->savePath;

        // Ubah kriteria: anggap aktif jika ada aktivitas dalam 5 menit terakhir (300 detik)
        $timeLimit = time() - 300;

        // Ambil semua session aktif
        $sessions = $db->table($sessionTable)
            ->where('timestamp >', $timeLimit)
            ->get()
            ->getResultArray();

        $userModel = new \App\Models\UserModel();
        $activeUsers = [];
        $processedUserIds = [];

        foreach ($sessions as $sess) {
            $data = $sess['data'];

            // --- PERBAIKAN DEFINITIF ---
            // Coba cari user_id yang disimpan sebagai integer (i:) atau string (s:).
            // Ini membuat fungsi lebih andal terhadap berbagai format data sesi.
            // Hanya proses jika sesi ini milik pengguna yang sudah login.
            $userId = null;
            if (strpos($data, 'isLoggedIn|b:1') !== false) {
                if (preg_match('/user_id\|i:(\d+);/', $data, $matches)) { // Cek format integer
                    $userId = $matches[1];
                } elseif (preg_match('/user_id\|s:\d+:"(\d+)";/', $data, $matches)) { // Cek format string
                    $userId = $matches[1];
                }
                if ($userId) {
                    $processedUserIds[$userId] = true; // Gunakan key untuk mencegah duplikat
                }
            }
        }

        // Selalu pastikan pengguna saat ini ada di daftar untuk mengatasi race condition
        $currentUserId = session()->get('user_id');
        if ($currentUserId) {
            $processedUserIds[$currentUserId] = true;
        }

        // Ambil data lengkap untuk semua user ID yang aktif
        if (!empty($processedUserIds)) {
            $activeUsers = $userModel->select('id, nama, email, image, role_id')
                                     ->whereIn('id', array_keys($processedUserIds))
                                     ->findAll();
        }

        return $activeUsers;
    }

    /**
     * Endpoint API untuk mengambil daftar pengguna aktif dalam format JSON.
     * Digunakan untuk pembaruan real-time di dashboard.
     */
    public function getActiveUsersJson()
    {
        // Pastikan ini adalah request AJAX untuk keamanan
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['users' => $this->getActiveUsers()]);
        }
        return $this->response->setStatusCode(403, 'Forbidden');
    }
}