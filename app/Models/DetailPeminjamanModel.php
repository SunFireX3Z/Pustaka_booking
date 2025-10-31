<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPeminjamanModel extends Model
{
    protected $table            = 'detail_peminjaman';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_pinjam', 'id_buku', 'tgl_kembali'];
}