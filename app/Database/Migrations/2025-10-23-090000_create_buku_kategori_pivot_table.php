<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBukuKategoriPivotTable extends Migration
{
    public function up()
    {
        // 1. Buat tabel pivot buku_kategori jika belum ada
        if (!$this->db->tableExists('buku_kategori')) {
            $this->forge->addField([
                'buku_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'kategori_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
            ]);
            $this->forge->addForeignKey('buku_id', 'buku', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('kategori_id', 'kategori', 'id_kategori', 'CASCADE', 'CASCADE');
            $this->forge->createTable('buku_kategori');
        }

        // 2. Hapus foreign key dan kolom id_kategori dari tabel buku
        if ($this->db->fieldExists('id_kategori', 'buku')) {
            // Cek dan hapus foreign key dulu.
            // Nama constraint bisa berbeda, tapi kita asumsikan namanya 'buku_id_kategori_foreign' sesuai error.
            $foreignKeys = $this->db->getForeignKeyData('buku');
            foreach ($foreignKeys as $fk) {
                if (in_array('id_kategori', $fk->column_name)) {
                    $this->forge->dropForeignKey('buku', $fk->constraint_name);
                    break; // Asumsi hanya ada satu FK di kolom ini
                }
            }
            // Baru hapus kolomnya
            $this->forge->dropColumn('buku', 'id_kategori'); 
        }
    }

    public function down()
    {
        // 1. Tambahkan kembali kolom id_kategori ke tabel buku jika belum ada
        if (!$this->db->fieldExists('id_kategori', 'buku')) {
            $this->forge->addColumn('buku', [
                'id_kategori' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'judul_buku']
            ]);
            // Tambahkan kembali foreign key
            $this->forge->addForeignKey('id_kategori', 'kategori', 'id_kategori', 'SET NULL', 'SET NULL', 'buku_id_kategori_foreign');
        }
        // 2. Hapus tabel pivot
        $this->forge->dropTable('buku_kategori', true); // true untuk if exists
    }
}