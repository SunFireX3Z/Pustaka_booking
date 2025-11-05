<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBannerToProfile extends Migration
{
    public function up()
    {
        $this->forge->addColumn('web_profile', [
            'banner_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'logo' // Letakkan setelah kolom logo
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('web_profile', 'banner_image');
    }
}