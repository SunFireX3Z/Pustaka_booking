<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\BeritaModel;
use App\Models\UserModel;
use App\Models\KategoriModel;
use App\Models\WebProfileModel;

class Member extends BaseController
{
    public function index()
    {
        $bukuModel = new BukuModel();
        $peminjamanModel = new PeminjamanModel();
        $bookingModel = new BookingModel();
        $beritaModel = new BeritaModel();
        $userId = session()->get('user_id');

        $data = [
            'title' => 'Homepage Member',
            'nama_member' => session()->get('nama'),
            // Query yang diperbaiki untuk mengambil buku terbaru
            'buku_terbaru' => $bukuModel
                ->select('buku.*, GROUP_CONCAT(kategori.nama_kategori SEPARATOR ", ") as kategori_nama')
                ->join('buku_kategori', 'buku_kategori.buku_id = buku.id', 'left')
                ->join('kategori', 'kategori.id_kategori = buku_kategori.kategori_id', 'left')
                ->groupBy('buku.id')
                ->orderBy('buku.id', 'DESC')
                ->limit(8)
                ->findAll(),
            'jumlah_dipinjam' => $peminjamanModel->where('id_user', $userId)->where('status', 'dipinjam')->countAllResults(),
            'jumlah_dibooking' => $bookingModel->getBookingCount($userId),
            'berita_list' => $beritaModel
                ->select('berita.*, kategori_berita.nama_kategori')
                ->join('kategori_berita', 'kategori_berita.id = berita.id_kategori_berita', 'left')
                ->where('berita.status', 'published')
                ->orderBy('berita.created_at', 'DESC')
                ->limit(6)->findAll(),
            'cart_count' => $bookingModel->getBookingCount($userId),
            'online_berita' => $this->getOnlineNews(),
            'current_page' => 'home', // Menandakan halaman aktif
            'web_profile' => get_web_profile(), // Menambahkan data profil web
        ];

        return view('pages/member/homepage', $data);
    }

    private function getOnlineNews()
    {
        $client = \Config\Services::curlrequest();
        $apiKey = '35fb0617924a4221a4f94801a60e5c39';
        $url = "https://newsapi.org/v2/everything?q=teknologi+indonesia&language=id&sortBy=publishedAt&pageSize=4&apiKey={$apiKey}";

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'CodeIgniter-NewsClient'
                ],
                'timeout' => 5,
                'http_errors' => false,
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                if (!empty($body['articles'])) {
                    return $body['articles'];
                }
            } else {
                log_message('error', 'NewsAPI balas status: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal mengambil berita dari NewsAPI: ' . $e->getMessage());
        }

        return [];
    }

    public function detailBuku($id)
    {
        $bukuModel = new BukuModel();
        $bookingModel = new BookingModel();

        // 1. Ambil data buku utama menggunakan method baru dari model
        $buku = $bukuModel->findBookWithCategories($id);

        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Buku dengan ID $id tidak ditemukan.");
        }

        // 2. Ambil ID kategori pertama untuk mencari buku terkait
        $kategoriIdUtama = !empty($buku['kategori_ids']) ? explode(',', $buku['kategori_ids'])[0] : null;

        // 3. Panggil method getRelatedBooks dari model
        $bukuTerkait = $bukuModel->getRelatedBooks(
            $kategoriIdUtama, // ID Kategori
            $buku['id'],      // ID Buku utama untuk dikecualikan
            5                 // Jumlah buku terkait yang ingin ditampilkan
        );

        $data = [
            'title'        => $buku['judul_buku'],
            'buku_terkait' => $bukuTerkait, // Kirim data buku terkait ke view
            'cart_count'   => $bookingModel->getBookingCount(session()->get('user_id')),
            'web_profile'  => get_web_profile(), // Memastikan data profil web diteruskan
            'buku'         => $buku, // Memperbaiki variabel buku yang hilang
        ];
    
        return view('pages/member/detail_buku', $data);
    }

    public function detailBerita($id)
    {
        $beritaModel = new BeritaModel();
        $bookingModel = new BookingModel();
    
        $berita = $beritaModel
            ->select('berita.*, kategori_berita.nama_kategori')
            ->join('kategori_berita', 'kategori_berita.id = berita.id_kategori_berita')
            ->where('berita.status', 'published')
            ->where('berita.id', $id)
            ->find($id);
    
        if (!$berita) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Berita dengan ID $id tidak ditemukan atau belum dipublikasikan.");
        }
    
        $data = [
            'title' => $berita['judul'],
            'berita' => $berita,
            'cart_count' => $bookingModel->getBookingCount(session()->get('user_id')),
            'online_berita_sidebar' => $this->getOnlineNews(), // Menambahkan berita online untuk sidebar
            'web_profile' => get_web_profile(), // Menambahkan data profil web
        ];

        return view('pages/member/detail_berita', $data);
    }

    public function bookNow($bookId)
    {
        $bukuModel = new BukuModel();
        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $userId = session()->get('user_id');
        $webProfileModel = new WebProfileModel();

        // 1. Validasi dasar
        if (!$userId) {
            return redirect()->to('/login')->with('msg', 'Silakan login untuk melakukan booking.');
        }

        $buku = $bukuModel->find($bookId);
        if (!$buku || $buku['stok'] <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia atau stok habis.');
        }

        // 2. Cek apakah user sudah punya booking pending
        $existingBooking = $bookingModel->where('id_user', $userId)->where('status', 'pending')->first();

        // 3. Cek limit booking dari profil web
        $profile = $webProfileModel->find(1) ?? ['max_buku_pinjam' => 3];
        $limitBooking = $profile['max_buku_pinjam'];
        if ($existingBooking) {
            $count = $detailBookingModel->where('id_booking', $existingBooking['id_booking'])->countAllResults();
            if ($count >= $limitBooking) {
                return redirect()->to('/member/keranjang')->with('error', "Anda sudah mencapai batas maksimal booking ({$limitBooking} buku). Selesaikan booking Anda saat ini terlebih dahulu.");
            }
            // Cek apakah buku yang sama sudah dibooking
            $isAlreadyBooked = $detailBookingModel->where('id_booking', $existingBooking['id_booking'])->where('id_buku', $bookId)->first();
            if ($isAlreadyBooked) {
                return redirect()->back()->with('error', 'Anda sudah mem-booking buku ini.');
            }
        }

        // 4. Proses booking menggunakan transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $bookingId = null;
            if ($existingBooking) {
                $bookingId = $existingBooking['id_booking'];
            } else {
                // Buat record booking baru jika belum ada
                $bookingModel->insert([
                    'id_user' => $userId, // Menggunakan id_user agar konsisten
                    'tanggal_booking' => date('Y-m-d'),
                    'status' => 'pending'
                ]);
                $bookingId = $bookingModel->getInsertID();
            }

            // Tambahkan buku ke detail booking
            $detailBookingModel->insert(['id_booking' => $bookingId, 'id_buku' => $bookId]);

            // Update stok dan jumlah dibooking pada tabel buku
            $bukuModel->set('stok', 'stok - 1', false)->set('dibooking', 'dibooking + 1', false)->where('id', $bookId)->update();

            $db->transComplete();

            return redirect()->to('/member/keranjang')->with('success', 'Buku berhasil ditambahkan ke keranjang booking Anda.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses booking. Silakan coba lagi.');
        }
    }

    public function profile()
    {
        $userId = session()->get('user_id');

        // Tampilkan halaman dengan data dari helper method
        $data = $this->getProfileViewData($userId);
        $data['title'] = 'Profil Saya';
        return view('pages/member/profile', $data);
    }

    /**
     * Helper method to get all necessary data for the profile view.
     * This avoids code duplication for GET request and failed POST request.
     */
    private function getProfileViewData($userId)
    {
        $userModel = new UserModel();
        $peminjamanModel = new PeminjamanModel();
        $bookingModel = new BookingModel();

        // Ambil data riwayat peminjaman
        $riwayat_peminjaman = $peminjamanModel
            ->select('peminjaman.*, GROUP_CONCAT(buku.judul_buku SEPARATOR "||") as daftar_buku')
            ->join('detail_peminjaman', 'detail_peminjaman.id_pinjam = peminjaman.id_pinjam', 'left')
            ->join('buku', 'buku.id = detail_peminjaman.id_buku', 'left')
            ->where('peminjaman.id_user', $userId)
            ->groupBy('peminjaman.id_pinjam')
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC')
            ->findAll();

        return [
            'user' => $userModel->find($userId),
            'validation' => \Config\Services::validation(), // Sediakan instance validation default
            'cart_count' => $bookingModel->getBookingCount($userId),
            'riwayat_peminjaman' => $riwayat_peminjaman,
            'web_profile' => get_web_profile(), // Menambahkan data profil web
        ];
    }

    public function keranjang()
    {
        $detailBookingModel = new DetailBookingModel();
        $bookingModel = new BookingModel();
        $userId = session()->get('user_id');

        $keranjang = [];
        $booking = $bookingModel->where('id_user', $userId)->where('status', 'pending')->first();
        
        $data['user'] = (new UserModel())->find($userId);

        if ($booking) {
            $keranjang = $detailBookingModel
                ->select('detail_booking.id_detail, buku.*')
                ->join('buku', 'buku.id = detail_booking.id_buku')
                ->where('detail_booking.id_booking', $booking['id_booking'])
                ->findAll();
        }

        $data = [
            'title'       => 'Keranjang Booking',
            'keranjang'   => $keranjang,
            'cart_count'  => count($keranjang),
            'web_profile' => get_web_profile(),
            'current_page' => 'keranjang', // Menandakan halaman aktif
        ];

        return view('pages/member/keranjang', $data);
    }

    public function hapusDariKeranjang($detailId)
    {
        $detailBookingModel = new DetailBookingModel();
        $bookingModel = new BookingModel();
        $bukuModel = new BukuModel();
        $userId = session()->get('user_id');

        $item = $detailBookingModel->find($detailId);

        // Validasi: pastikan item ada dan milik user yang sedang login
        if (!$item) {
            return redirect()->to('/member/keranjang')->with('error', 'Item tidak ditemukan.');
        }

        $booking = $bookingModel->find($item['id_booking']);
        if (!$booking || $booking['id_user'] != $userId || $booking['status'] != 'pending') {
            return redirect()->to('/member/keranjang')->with('error', 'Aksi tidak diizinkan.');
        }
    
        $db = \Config\Database::connect();
        $db->transStart();
    
        // Kembalikan stok buku
        $bukuModel->set('stok', 'stok + 1', false)->set('dibooking', 'dibooking - 1', false)->where('id', $item['id_buku'])->update();
        // Hapus item dari detail booking
        $detailBookingModel->delete($detailId);
    
        $db->transComplete();
    
        return redirect()->to('/member/keranjang')->with('success', 'Buku berhasil dihapus dari keranjang.');
    }

    public function batalKeranjang()
    {
        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $bukuModel = new BukuModel();
        $userId = session()->get('user_id');

        // 1. Cari booking yang masih pending
        $booking = $bookingModel->where('id_user', $userId)->where('status', 'pending')->first();

        if (!$booking) {
            return redirect()->to('/member/keranjang')->with('error', 'Tidak ada keranjang aktif untuk dibatalkan.');
        }

        // 2. Ambil semua item di keranjang
        $items = $detailBookingModel->where('id_booking', $booking['id_booking'])->findAll();

        // 3. Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 4. Kembalikan stok untuk setiap buku
            if (!empty($items)) {
                foreach ($items as $item) {
                    $bukuModel->set('stok', 'stok + 1', false)->set('dibooking', 'dibooking - 1', false)->where('id', $item['id_buku'])->update();
                }
            }

            // 5. Hapus record booking dan detailnya
            $detailBookingModel->where('id_booking', $booking['id_booking'])->delete();
            $bookingModel->delete($booking['id_booking']);

            $db->transComplete();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Keranjang booking Anda telah dikosongkan.']);
            }
            return redirect()->to('/member/katalog')->with('success', 'Keranjang booking Anda telah dikosongkan.');
        } catch (\Exception $e) {
            $db->transRollback();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal membatalkan keranjang.']);
            }
            return redirect()->to('/member/keranjang')->with('error', 'Gagal membatalkan keranjang.');
        }
    }

    public function selesaikanBooking()
    {
        $bookingModel = new BookingModel();
        $userId = session()->get('user_id');

        // 1. Pastikan method diakses via POST
        // Menggunakan is('post') untuk pengecekan yang lebih andal
        if (!$this->request->is('post')) {
            return redirect()->to('/member/keranjang')->with('error', 'Aksi tidak valid.');
        }

        // 2. Cari booking yang masih pending
        $booking = $bookingModel->where('id_user', $userId)->where('status', 'pending')->first();

        if (!$booking) {
            return redirect()->to('/member/keranjang')->with('error', 'Tidak ada booking aktif yang bisa diselesaikan.');
        }

        // 3. Update status booking menjadi 'dibooking'
        // Anda juga bisa menambahkan batas waktu pengambilan di sini
        $dataToUpdate = [
            'status' => 'dibooking',
            // Contoh: 'batas_ambil' => date('Y-m-d H:i:s', strtotime('+1 day'))
        ];

        $bookingModel->update($booking['id_booking'], $dataToUpdate);

        // Jika ini adalah request AJAX, kirim response JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Booking Anda telah dikonfirmasi!']);
        }

        return redirect()->to('/member/katalog')->with('success', 'Booking Anda telah dikonfirmasi!');
    }    

    public function katalog()
    {
        $bukuModel = new BukuModel();
        $kategoriModel = new KategoriModel();
        $bookingModel = new BookingModel();
        $userId = session()->get('user_id');

        $keyword = $this->request->getGet('keyword');
        $kategoriId = $this->request->getGet('kategori');

        $bukuQuery = $bukuModel->select('buku.*, GROUP_CONCAT(kategori.nama_kategori SEPARATOR ", ") as kategori_nama')
                               ->join('buku_kategori', 'buku_kategori.buku_id = buku.id', 'left')
                               ->join('kategori', 'kategori.id_kategori = buku_kategori.kategori_id', 'left');

        if ($keyword) {
            $bukuQuery->like('judul_buku', $keyword)->orLike('pengarang', $keyword);
        }
        if ($kategoriId) {
            // Perbaiki query untuk filter berdasarkan kategori di tabel pivot
            $bukuQuery->whereIn('buku.id', function($builder) use ($kategoriId) {
                return $builder->select('buku_id')->from('buku_kategori')->where('kategori_id', $kategoriId);
            });
        }

        $allBooks = $bukuQuery->groupBy('buku.id')->orderBy('buku.judul_buku', 'ASC')->findAll();

        // Kelompokkan buku berdasarkan kategori
        $groupedBooks = [];
        foreach ($allBooks as $book) {
            $categoryName = explode(', ', $book['kategori_nama'])[0] ?? 'Lainnya';
            $groupedBooks[$categoryName][] = $book;
        }

        $data = [
            'title' => 'Katalog Buku',
            'grouped_buku' => $groupedBooks,
            'kategori' => $kategoriModel->findAll(),
            'cart_count' => $bookingModel->getBookingCount($userId),
            'keyword' => $keyword,
            'selected_kategori' => $kategoriId,
            'web_profile' => get_web_profile(), // Menambahkan data profil web
            'current_page' => 'katalog', // Menandakan halaman aktif
        ];

        return view('pages/member/katalog', $data);
    }
}