<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailBookingModel extends Model
{
    protected $table            = 'detail_booking';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_booking', 'id_buku'];
}