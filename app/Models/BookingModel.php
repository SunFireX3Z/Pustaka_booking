<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'booking';
    protected $primaryKey       = 'id_booking';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'tanggal_booking', 'status', 'batas_ambil'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Mengambil jumlah buku yang sedang di-booking oleh user (status 'pending').
     *
     * @param int $userId
     * @return int
     */
    public function getBookingCount(int $userId): int
    {
        $booking = $this->where('id_user', $userId)->where('status', 'pending')->first();
        if (!$booking) {
            return 0;
        }

        $detailBookingModel = new DetailBookingModel();
        return $detailBookingModel->where('id_booking', $booking['id_booking'])->countAllResults();
    }
}