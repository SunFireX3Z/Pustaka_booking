<?php 
namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table      = 'buku';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul_buku', 'id_kategori', 'pengarang', 'penerbit',
        'tahun_terbit', 'isbn', 'stok', 'dipinjam', 'dibooking', 'image', 'hipotesis'
    ];
}
