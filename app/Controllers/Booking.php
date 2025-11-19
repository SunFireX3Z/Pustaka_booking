<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\DetailPeminjamanModel;
use App\Models\WebProfileModel;

class Booking extends BaseController
{
    public function index()
    {
        $bookingModel = new BookingModel();

        // Mengambil data booking beserta nama user dan detail buku yang dibooking
        $data['bookings'] = $bookingModel->select('booking.*, user.nama as nama_user, COUNT(detail_booking.id_buku) as jumlah_buku')
            ->join('user', 'user.id = booking.id_user')
            ->join('detail_booking', 'detail_booking.id_booking = booking.id_booking', 'left')
            ->whereIn('booking.status', ['dibooking', 'disetujui', 'dibatalkan']) // Hanya tampilkan status yang relevan untuk admin
            ->groupBy('booking.id_booking')
            ->orderBy('booking.tanggal_booking', 'DESC')
            ->findAll();

        return view('pages/admin/booking', $data);
    }

    public function approve($bookingId)
    {
        // Load model yang diperlukan
        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $peminjamanModel = new PeminjamanModel();
        $detailPeminjamanModel = new DetailPeminjamanModel();
        $bukuModel = new BukuModel();
        $webProfileModel = new WebProfileModel();

        // 1. Validasi booking
        $booking = $bookingModel->find($bookingId);
        if (!$booking || $booking['status'] !== 'dibooking') {
            return redirect()->to('/booking')->with('error', 'Booking tidak valid atau sudah diproses.');
        }

        // 2. Ambil semua buku yang di-booking
        $bookedBooks = $detailBookingModel->where('id_booking', $bookingId)->findAll();
        if (empty($bookedBooks)) {
            $bookingModel->update($bookingId, ['status' => 'dibatalkan']);
            return redirect()->to('/booking')->with('error', 'Booking tidak memiliki buku, dibatalkan secara otomatis.');
        }

        // Ambil pengaturan dari profil web
        $profile = $webProfileModel->find(1) ?? ['max_hari_pinjam' => 7];

        // 3. Mulai transaksi database untuk memastikan semua proses berhasil
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 4. Ubah status booking menjadi 'disetujui'
            $bookingModel->update($bookingId, ['status' => 'disetujui']);

            // 5. Buat entri peminjaman baru
            $peminjamanModel->insert([
                'id_user' => $booking['id_user'],
                'tanggal_pinjam' => date('Y-m-d'),
                'tanggal_kembali' => date('Y-m-d', strtotime('+' . $profile['max_hari_pinjam'] . ' days')), // Menggunakan pengaturan dari profil web
                'status' => 'dipinjam'
            ]);
            $peminjamanId = $peminjamanModel->getInsertID();

            // 6. Pindahkan buku dari detail_booking ke detail_peminjaman dan update status buku
            foreach ($bookedBooks as $detail) {
                $detailPeminjamanModel->insert([
                    'id_pinjam' => $peminjamanId,
                    'id_buku' => $detail['id_buku'],
                ]);
                // Update status buku: kurangi 'dibooking', tambah 'dipinjam'
                $bukuModel->set('dibooking', 'dibooking - 1', false)->set('dipinjam', 'dipinjam + 1', false)->where('id', $detail['id_buku'])->update(null);
            }

            $db->transComplete();
            return redirect()->to('/booking')->with('success', 'Booking berhasil disetujui dan transaksi peminjaman telah dibuat.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/booking')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($bookingId)
    {
        // Load model yang diperlukan
        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $bukuModel = new BukuModel();

        // 1. Validasi booking
        $booking = $bookingModel->find($bookingId);
        if (!$booking || $booking['status'] !== 'dibooking') {
            return redirect()->to('/booking')->with('error', 'Booking tidak valid atau sudah diproses.');
        }

        // 2. Ambil semua buku yang di-booking
        $bookedBooks = $detailBookingModel->where('id_booking', $bookingId)->findAll();

        // 3. Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 4. Ubah status booking menjadi 'dibatalkan'
            $bookingModel->update($bookingId, ['status' => 'dibatalkan']);

            // 5. Kembalikan stok buku jika ada buku yang dibooking
            if (!empty($bookedBooks)) {
                foreach ($bookedBooks as $detail) {
                    $bukuModel->set('stok', 'stok + 1', false)->set('dibooking', 'dibooking - 1', false)->where('id', $detail['id_buku'])->update(null);
                }
            }

            $db->transComplete();
            return redirect()->to('/booking')->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/booking')->with('error', 'Terjadi kesalahan saat membatalkan booking: ' . $e->getMessage());
        }
    }

    public function delete($bookingId)
    {
        // Load model yang diperlukan
        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();

        // 1. Validasi booking
        $booking = $bookingModel->find($bookingId);
        if (!$booking) {
            return redirect()->to('/booking')->with('error', 'Data booking tidak ditemukan.');
        }

        // Hanya booking yang sudah selesai/dibatalkan yang bisa dihapus
        if (!in_array($booking['status'], ['disetujui', 'dibatalkan'])) {
            return redirect()->to('/booking')->with('error', 'Hanya booking yang sudah selesai atau dibatalkan yang dapat dihapus.');
        }

        // 2. Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Hapus detail booking terlebih dahulu
            $detailBookingModel->where('id_booking', $bookingId)->delete();
            // Hapus booking utama
            $bookingModel->delete($bookingId);

            $db->transComplete();
            return redirect()->to('/booking')->with('success', 'Data booking berhasil dihapus.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/booking')->with('error', 'Gagal menghapus data booking: ' . $e->getMessage());
        }
    }

	public function getDetailBooking($id_booking)
	{
		$detailBookingModel = new DetailBookingModel();

		$details = $detailBookingModel
            ->select('buku.judul_buku, booking.id_booking, booking.tanggal_booking, booking.status, user.nama as nama_user')
            ->join('buku', 'buku.id = detail_booking.id_buku')
            ->join('booking', 'booking.id_booking = detail_booking.id_booking')
            ->join('user', 'user.id = booking.id_user')
            ->where('detail_booking.id_booking', $id_booking)
            ->findAll();

		return $this->response->setJSON($details);
	}


}