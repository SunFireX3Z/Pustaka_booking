<?php

namespace App\Models;

use CodeIgniter\Model;

class DendaModel extends Model
{
    protected $table            = 'denda';
    protected $primaryKey       = 'id_denda';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_user', 'id_pinjam', 'jumlah_denda', 'status'];
}