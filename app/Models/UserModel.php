<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama', 'email', 'image', 'password', 'role_id', 'is_active', 'tanggal_input',
        // Field sekolah yang sudah ada
        'nis', 'nisn', 'kelas', 'jurusan',
        // Field baru yang ditambahkan
        'no_hp', 'tanggal_lahir', 'jenis_kelamin', 'nik'
    ];
    protected $useTimestamps = false; // Sesuaikan jika Anda menggunakan created_at/updated_at otomatis
}