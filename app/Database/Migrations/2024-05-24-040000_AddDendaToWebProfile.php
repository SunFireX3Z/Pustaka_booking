<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDendaToWebProfile extends Migration
{
    public function up()
    {
        $this->forge->addColumn('web_profile', [
            'denda_per_hari' => ['type' => 'INT', 'constraint' => 11, 'default' => 1000, 'after' => 'max_hari_pinjam'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('web_profile', 'denda_per_hari');
    }
}