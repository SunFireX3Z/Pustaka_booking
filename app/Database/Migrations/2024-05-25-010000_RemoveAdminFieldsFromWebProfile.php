<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveAdminFieldsFromWebProfile extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('web_profile', ['nama_pengelola', 'no_hp_pengelola', 'email_pengelola']);
    }

    public function down()
    {
        // Metode down untuk mengembalikan kolom jika migrasi di-rollback
        $this->forge->addColumn('web_profile', [
            'nama_pengelola' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true, 'after' => 'jabatan_penandatangan_mou'],
            'no_hp_pengelola' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'nama_pengelola'],
            'email_pengelola' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true, 'after' => 'no_hp_pengelola'],
        ]);
    }
}
