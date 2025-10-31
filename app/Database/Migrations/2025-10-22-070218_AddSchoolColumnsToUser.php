<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSchoolColumnsToUser extends Migration
{
    public function up()
    {
        $fields = [
            'nis' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'email',
            ],
            'nisn' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'nis',
            ],
            'kelas' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'nisn',
            ],
            'jurusan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'kelas',
            ],
        ];

        $this->forge->addColumn('user', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user', ['nis', 'nisn', 'kelas', 'jurusan']);
    }
}