<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWebProfileTable extends Migration
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
            'nama_instansi' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'nama_aplikasi' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kabupaten_kota' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'npwp' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'nama_penanggung_jawab' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'jabatan_penanggung_jawab' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'nama_penandatangan_mou' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'jabatan_penandatangan_mou' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'nama_pengelola' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'no_hp_pengelola' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'email_pengelola' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'max_buku_pinjam' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 3,
            ],
            'max_hari_pinjam' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 7,
            ],
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => 'default_logo.png',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('web_profile');
    }

    public function down()
    {
        $this->forge->dropTable('web_profile');
    }
}