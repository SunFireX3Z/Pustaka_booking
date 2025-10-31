<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriBeritaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_berita');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_berita');
    }
}