<?php

namespace App\Models;

use CodeIgniter\Model;

class WebProfileModel extends Model
{
    protected $table            = 'web_profile';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields = [
        'nama_instansi', 'nama_aplikasi', 'alamat', 'kabupaten_kota', 'npwp',
        'nama_penanggung_jawab', 'jabatan_penanggung_jawab',
        'nama_penandatangan_mou', 'jabatan_penandatangan_mou',
        'max_buku_pinjam', 'max_hari_pinjam', 'denda_per_hari', 'logo', 'banner_image'
    ];

    // Dates
    protected $useTimestamps = true;
}