<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePerpustakaanTables extends Migration
{
    public function up()
    {
        // 1. ROLE
        $this->forge->addField([
            'id'   => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'role' => ['type' => 'VARCHAR','constraint' => '50'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('role', true);

        // 2. USER
        $this->forge->addField([
            'id'            => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'nama'          => ['type' => 'VARCHAR','constraint' => '100'],
            'email'         => ['type' => 'VARCHAR','constraint' => '100'],
            'image'         => ['type' => 'VARCHAR','constraint' => '255','null' => true],
            'password'      => ['type' => 'VARCHAR','constraint' => '255'],
            'role_id'       => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'is_active'     => ['type' => 'TINYINT','constraint' => 1,'default' => 1],
            'tanggal_input' => ['type' => 'DATETIME','null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'role', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user', true);

        // 3. KATEGORI
        $this->forge->addField([
            'id_kategori'   => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'nama_kategori' => ['type' => 'VARCHAR','constraint' => '100'],
        ]);
        $this->forge->addKey('id_kategori', true);
        $this->forge->createTable('kategori', true);

        // 4. BUKU
        $this->forge->addField([
            'id'           => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'judul_buku'   => ['type' => 'VARCHAR','constraint' => '255'],
            'id_kategori'  => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'pengarang'    => ['type' => 'VARCHAR','constraint' => '100'],
            'penerbit'     => ['type' => 'VARCHAR','constraint' => '100'],
            'tahun_terbit' => ['type' => 'YEAR'],
            'isbn'         => ['type' => 'VARCHAR','constraint' => '50'],
            'stok'         => ['type' => 'INT','constraint' => 11],
            'dipinjam'     => ['type' => 'INT','constraint' => 11,'default' => 0],
            'dibooking'    => ['type' => 'INT','constraint' => 11,'default' => 0],
            'deskripsi'    => ['type' => 'TEXT','null' => true],
            'image'        => ['type' => 'VARCHAR','constraint' => '255','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_kategori', 'kategori', 'id_kategori', 'CASCADE', 'CASCADE');
        $this->forge->createTable('buku', true);

        // 5. BOOKING
        $this->forge->addField([
            'id_booking'    => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'id_user'       => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'tanggal_booking'=> ['type' => 'DATE'],
            'status'        => ['type' => 'ENUM("pending","disetujui","dibatalkan")','default' => 'pending'],
        ]);
        $this->forge->addKey('id_booking', true);
        $this->forge->addForeignKey('id_user', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('booking', true);

        // 6. DETAIL_BOOKING
        $this->forge->addField([
            'id_detail'   => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'id_booking'  => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'id_buku'     => ['type' => 'INT','constraint' => 11,'unsigned' => true],
        ]);
        $this->forge->addKey('id_detail', true);
        $this->forge->addForeignKey('id_booking', 'booking', 'id_booking', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_buku', 'buku', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_booking', true);

        // 7. PEMINJAMAN
        $this->forge->addField([
            'id_pinjam'    => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'id_user'      => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'tanggal_pinjam'=> ['type' => 'DATE'],
            'tanggal_kembali'=> ['type' => 'DATE'],
            'tanggal_dikembalikan' => ['type' => 'DATE', 'null' => true],
            'total_denda'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'status'       => ['type' => 'ENUM("dipinjam","kembali")','default' => 'dipinjam'],
        ]);
        $this->forge->addKey('id_pinjam', true);
        $this->forge->addForeignKey('id_user', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('peminjaman', true);

        // 8. DETAIL_PEMINJAMAN
        $this->forge->addField([
            'id_detail'   => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'id_pinjam'   => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'id_buku'     => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'tgl_kembali' => ['type' => 'DATE','null' => true],
        ]);
        $this->forge->addKey('id_detail', true);
        $this->forge->addForeignKey('id_pinjam', 'peminjaman', 'id_pinjam', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_buku', 'buku', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_peminjaman', true);

        // 9. DENDA
        $this->forge->addField([
            'id_denda'    => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'id_user'     => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'id_pinjam'   => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'jumlah_denda'=> ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'status'      => ['type' => 'ENUM("belum bayar","sudah bayar")','default' => 'belum bayar'],
            'tanggal_bayar' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_denda', true);
        $this->forge->addForeignKey('id_user', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pinjam', 'peminjaman', 'id_pinjam', 'CASCADE', 'CASCADE');
        $this->forge->createTable('denda', true);
    }

    public function down()
    {
        $this->forge->dropTable('denda', true);
        $this->forge->dropTable('detail_peminjaman', true);
        $this->forge->dropTable('peminjaman', true);
        $this->forge->dropTable('detail_booking', true);
        $this->forge->dropTable('booking', true);
        $this->forge->dropTable('buku', true);
        $this->forge->dropTable('kategori', true);
        $this->forge->dropTable('user', true);
        $this->forge->dropTable('role', true);
    }
}
