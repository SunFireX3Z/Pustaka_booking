<?php
namespace App\Models;

use CodeIgniter\Model;

class BukuKategoriModel extends Model
{
    protected $table = 'buku_kategori';
    protected $allowedFields = ['buku_id', 'kategori_id'];
    // Tidak ada primary key, jadi kita tidak set $primaryKey
}