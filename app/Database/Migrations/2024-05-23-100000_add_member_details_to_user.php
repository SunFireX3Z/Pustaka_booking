<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMemberDetailsToUser extends Migration
{
    public function up()
    {
        $fields = [
            'no_hp' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => true,
                'after' => 'jurusan',
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'no_hp',
            ],
            'jenis_kelamin' => [
                'type' => 'ENUM',
                'constraint' => ['Laki-laki', 'Perempuan'],
                'null' => true,
                'after' => 'tanggal_lahir',
            ],
            'nik' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'jenis_kelamin',
            ],
        ];
        $this->forge->addColumn('user', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user', ['no_hp', 'tanggal_lahir', 'jenis_kelamin', 'nik']);
    }
}