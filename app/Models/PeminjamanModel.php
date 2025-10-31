<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id_pinjam';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_user', 'tanggal_pinjam', 'tanggal_kembali', 'status'];
}