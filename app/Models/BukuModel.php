<?php 
namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table      = 'buku';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul_buku', 'id_kategori', 'pengarang', 'penerbit',
        'tahun_terbit', 'isbn', 'eisbn', 'stok', 'dipinjam', 'dibooking', 'image', 'deskripsi', 'file_pdf'
    ];

    /**
     * Mengambil detail satu buku lengkap dengan nama dan ID kategorinya.
     *
     * @param int $id ID Buku
     * @return array|null
     */
    public function findBookWithCategories($id)
    {
        return $this->select('buku.*, 
            GROUP_CONCAT(kategori.nama_kategori SEPARATOR ", ") as kategori_nama, 
            GROUP_CONCAT(kategori.id_kategori SEPARATOR ",") as kategori_ids')
            ->join('buku_kategori', 'buku_kategori.buku_id = buku.id', 'left')
            ->join('kategori', 'kategori.id_kategori = buku_kategori.kategori_id', 'left')
            ->where('buku.id', $id)
            ->groupBy('buku.id')
            ->first(); // Menggunakan first() untuk hasil tunggal dari query custom
    }

    /**
     * Mengambil buku terkait berdasarkan kategori.
     *
     * @param int|null $categoryId ID kategori
     * @param int      $excludeBookId ID buku yang tidak akan ditampilkan
     * @param int      $limit Jumlah buku yang akan diambil
     * @return array
     */
    public function getRelatedBooks($categoryId, $excludeBookId, $limit = 5)
    {
        if (!$categoryId) {
            return [];
        }

        return $this->select('buku.*')
            ->join('buku_kategori', 'buku_kategori.buku_id = buku.id')
            ->where('buku_kategori.kategori_id', $categoryId)
            ->where('buku.id !=', $excludeBookId)
            ->orderBy('buku.judul_buku', 'RANDOM')
            ->limit($limit)->findAll();
    }
}
