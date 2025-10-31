<?php
namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table      = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $allowedFields = ['nama_kategori'];

    /**
     * Cek apakah sebuah kategori sedang digunakan oleh buku.
     *
     * @param int $kategoriId ID Kategori
     * @return bool
     */
    public function isUsed($kategoriId)
    {
        $bukuKategoriModel = new \App\Models\BukuKategoriModel();
        $count = $bukuKategoriModel->where('kategori_id', $kategoriId)->countAllResults();

        return $count > 0;
    }
}