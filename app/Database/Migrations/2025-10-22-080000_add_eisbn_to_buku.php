<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEisbnToBuku extends Migration
{
    public function up()
    {
        $fields = [
            'eisbn' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'isbn',
            ],
        ];
        $this->forge->addColumn('buku', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('buku', 'eisbn');
    }
}