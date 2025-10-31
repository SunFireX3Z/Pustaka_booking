<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table            = 'berita';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['judul', 'id_kategori_berita', 'isi_berita', 'thumbnail', 'status'];
}