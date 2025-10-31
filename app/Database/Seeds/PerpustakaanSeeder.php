<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use Faker\Factory;

class PerpustakaanSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');

        // 1. ROLE
        $this->db->table('role')->insertBatch([
            ['id' => 1, 'role' => 'admin'],
            ['id' => 2, 'role' => 'member'],
        ]);

        // 2. USER
        $this->db->table('user')->insertBatch([
            [
                'id' => 1, 'nama' => 'Administrator', 'email' => 'admin@perpus.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT), 'image' => 'default.jpg',
                'role_id' => 1, 'is_active' => 1, 'tanggal_input' => Time::now(),
                    'nis' => null,
                'nisn' => null,
                'kelas' => null,
                'jurusan' => null,
            ],
            [
                'id' => 2, 'nama' => 'Rafi Ahmad', 'email' => 'rafi@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT), 'image' => 'default.jpg',
                'role_id' => 2, 'is_active' => 1, 'tanggal_input' => Time::now(),
                'nis' => '12345', 'nisn' => '20251234', 'kelas' => 'XI IPA 2', 'jurusan' => 'Ilmu Pengetahuan Alam',
            ],
            [
                'id' => 3, 'nama' => 'Siti Nurhaliza', 'email' => 'siti@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT), 'image' => 'default.jpg',
                'role_id' => 2, 'is_active' => 1, 'tanggal_input' => Time::now(),
                'nis' => '12346', 'nisn' => '20251235', 'kelas' => 'XI RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak',
            ],
            [
                'id' => 4, 'nama' => 'Budi Santoso', 'email' => 'budi@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT), 'image' => 'default.jpg',
                'role_id' => 2, 'is_active' => 1, 'tanggal_input' => Time::now(),
                'nis' => '12347', 'nisn' => '20251236', 'kelas' => 'X TKJ 3', 'jurusan' => 'Teknik Komputer Jaringan',
            ],
        ]);

        // 3. KATEGORI
        $this->db->table('kategori')->insertBatch([
            ['id_kategori' => 1, 'nama_kategori' => 'Fiksi Ilmiah'],
            ['id_kategori' => 2, 'nama_kategori' => 'Pemrograman'],
            ['id_kategori' => 3, 'nama_kategori' => 'Sejarah'],
            ['id_kategori' => 4, 'nama_kategori' => 'Biografi'],
            ['id_kategori' => 5, 'nama_kategori' => 'Novel'],
            ['id_kategori' => 6, 'nama_kategori' => 'Sains'],
        ]);

        // 4. BUKU
        $bukuData = [];
        $judul = [
            'Dasar-Dasar Pemrograman PHP', 'Algoritma dan Struktur Data', 'Jaringan Komputer Modern',
            'Laskar Pelangi', 'Bumi Manusia', 'Negeri 5 Menara',
            'Sapiens: Riwayat Singkat Umat Manusia', 'Homo Deus: Masa Depan Umat Manusia',
            'Perang Dunia II di Pasifik', 'Majapahit: Batas Kota dan Jejak Kejayaan',
            'Biografi Steve Jobs', 'Einstein: Hidup dan Semestanya',
            'Dunia Sophie', 'Kosmos', 'The Selfish Gene'
        ];
        $pengarang = ['Andrea Hirata', 'Pramoedya Ananta Toer', 'Ahmad Fuadi', 'Yuval Noah Harari', 'Walter Isaacson', 'Jostein Gaarder', 'Carl Sagan', 'Richard Dawkins', 'R.M. A. B. Pranarka', 'Agus Aris Munandar'];
        $penerbit = ['Gramedia Pustaka Utama', 'Bentang Pustaka', 'Kepustakaan Populer Gramedia', 'Mizan'];

        for ($i = 0; $i < 15; $i++) {
            $bukuData[] = [
                'judul_buku'   => $judul[$i],
                'id_kategori'  => $faker->numberBetween(1, 6),
                'pengarang'    => $faker->randomElement($pengarang),
                'penerbit'     => $faker->randomElement($penerbit),
                'tahun_terbit' => $faker->numberBetween(2005, 2023),
                'isbn'         => $faker->isbn13(),
                'stok'         => $faker->numberBetween(3, 10),
                'deskripsi'    => $faker->paragraph(3),
                'image'        => 'default.jpg',
            ];
        }
        $this->db->table('buku')->insertBatch($bukuData);

        // 5. PEMINJAMAN (Contoh 1 transaksi aktif)
        $this->db->table('peminjaman')->insert([
            'id_pinjam' => 1,
            'id_user' => 3, // Siti Nurhaliza
            'tanggal_pinjam' => date('Y-m-d', strtotime('-3 days')),
            'tanggal_kembali' => date('Y-m-d', strtotime('+4 days')),
            'status' => 'dipinjam',
        ]);
        $this->db->table('detail_peminjaman')->insert(['id_pinjam' => 1, 'id_buku' => 2]); // Meminjam buku 'Algoritma'
        $this->db->table('buku')->where('id', 2)->set('stok', 'stok - 1', false)->set('dipinjam', 'dipinjam + 1', false)->update();

        // 6. BOOKING (Contoh 1 transaksi aktif)
        $this->db->table('booking')->insert([
            'id_booking' => 1,
            'id_user' => 4, // Budi Santoso
            'tanggal_booking' => date('Y-m-d'),
            'status' => 'pending',
        ]);
        $this->db->table('detail_booking')->insertBatch([
            ['id_booking' => 1, 'id_buku' => 4], // Booking 'Laskar Pelangi'
            ['id_booking' => 1, 'id_buku' => 7], // Booking 'Sapiens'
        ]);
        $this->db->table('buku')->whereIn('id', [4, 7])->set('stok', 'stok - 1', false)->set('dibooking', 'dibooking + 1', false)->update();
    }
}
