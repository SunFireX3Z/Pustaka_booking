<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Role
        $this->db->table('role')->insertBatch([
            ['role' => 'Administrator'],
            ['role' => 'Petugas'],
            ['role' => 'Member']
        ]);

        // User
        $this->db->table('user')->insertBatch([
            [
                'nama'          => 'Admin Utama',
                'email'         => 'admin@example.com',
                'image'         => 'default.png',
                'password'      => password_hash('123456', PASSWORD_DEFAULT),
                'role_id'       => 1,
                'is_active'     => 1,
                'tanggal_input' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'          => 'Petugas Perpustakaan',
                'email'         => 'petugas@example.com',
                'image'         => 'default.png',
                'password'      => password_hash('123456', PASSWORD_DEFAULT),
                'role_id'       => 2,
                'is_active'     => 1,
                'tanggal_input' => date('Y-m-d H:i:s'),
            ]
        ]);

        // Kategori
        $this->db->table('kategori')->insertBatch([
            ['nama_kategori' => 'Teknologi'],
            ['nama_kategori' => 'Fiksi'],
            ['nama_kategori' => 'Sejarah']
        ]);

        // Buku
        $this->db->table('buku')->insertBatch([
            [
                'judul_buku'  => 'Belajar PHP Modern',
                'id_kategori' => 1,
                'pengarang'   => 'Randy Saputra',
                'penerbit'    => 'Informatika Press',
                'tahun_terbit'=> 2023,
                'isbn'        => '978-602-1234-567',
                'stok'        => 10,
                'dipinjam'    => 0,
                'dibooking'   => 0,
                'image'       => 'php.jpg',
            ],
            [
                'judul_buku'  => 'Sejarah Nusantara',
                'id_kategori' => 3,
                'pengarang'   => 'Ahmad Santoso',
                'penerbit'    => 'Sejarah Kita',
                'tahun_terbit'=> 2020,
                'isbn'        => '978-602-7654-321',
                'stok'        => 5,
                'dipinjam'    => 0,
                'dibooking'   => 0,
                'image'       => 'nusantara.jpg',
            ]
        ]);
    }
}
