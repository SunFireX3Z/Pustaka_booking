<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'booking';
    protected $primaryKey       = 'id_booking';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_user', 'tanggal_booking', 'status', 'batas_ambil'];

    /**
     * Menghitung jumlah buku dalam keranjang booking yang sedang pending milik user.
     *
     * @param int $userId ID user
     * @return int Jumlah buku di keranjang
     */
    public function getBookingCount($userId)
    {
        // Cari booking yang statusnya 'pending' untuk user ini
        $booking = $this->where('id_user', $userId)
                        ->where('status', 'pending')
                        ->first();

        if (!$booking) {
            return 0; // Jika tidak ada booking pending, keranjang kosong.
        }

        // Hitung jumlah item di detail_booking berdasarkan id_booking
        $detailBookingModel = new \App\Models\DetailBookingModel();
        return $detailBookingModel->where('id_booking', $booking['id_booking'])->countAllResults();
    }
}