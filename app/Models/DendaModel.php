<?php

namespace App\Models;

use CodeIgniter\Model;

class DendaModel extends Model
{
    protected $table            = 'denda';
    protected $primaryKey       = 'id_denda';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id_user', 'id_pinjam', 'jumlah_denda', 'status', 'tanggal_bayar'];
}