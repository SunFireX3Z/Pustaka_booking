<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInitialTables extends Migration
{
    public function up()
    {
        // 1. Tabel role
        $this->forge->addField([
            'id'   => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'role' => ['type' => 'VARCHAR','constraint' => '50'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('role', true);

        // 2. Tabel user
        $this->forge->addField([
            'id'           => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'nama'         => ['type' => 'VARCHAR','constraint' => '100'],
            'email'        => ['type' => 'VARCHAR','constraint' => '100'],
            'image'        => ['type' => 'VARCHAR','constraint' => '255','null' => true],
            'password'     => ['type' => 'VARCHAR','constraint' => '255'],
            'role_id'      => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'is_active'    => ['type' => 'TINYINT','constraint' => 1,'default' => 1],
            'tanggal_input'=> ['type' => 'DATETIME','null' => false], // default dihapus
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id','role','id','CASCADE','CASCADE');
        $this->forge->createTable('user', true);

        // 3. Tabel kategori
        $this->forge->addField([
            'id_kategori'  => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'nama_kategori'=> ['type' => 'VARCHAR','constraint' => '100'],
        ]);
        $this->forge->addKey('id_kategori', true);
        $this->forge->createTable('kategori', true);

        // 4. Tabel buku
        $this->forge->addField([
            'id'          => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'judul_buku'  => ['type' => 'VARCHAR','constraint' => '255'],
            'id_kategori' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'pengarang'   => ['type' => 'VARCHAR','constraint' => '100'],
            'penerbit'    => ['type' => 'VARCHAR','constraint' => '100'],
            'tahun_terbit'=> ['type' => 'YEAR'],
            'isbn'        => ['type' => 'VARCHAR','constraint' => '50'],
            'stok'        => ['type' => 'INT','constraint' => 11],
            'dipinjam'    => ['type' => 'INT','constraint' => 11,'default' => 0],
            'dibooking'   => ['type' => 'INT','constraint' => 11,'default' => 0],
            'image'       => ['type' => 'VARCHAR','constraint' => '255','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_kategori','kategori','id_kategori','CASCADE','CASCADE');
        $this->forge->createTable('buku', true);
    }

    public function down()
    {
        $this->forge->dropTable('buku', true);
        $this->forge->dropTable('kategori', true);
        $this->forge->dropTable('user', true);
        $this->forge->dropTable('role', true);
    }
}
