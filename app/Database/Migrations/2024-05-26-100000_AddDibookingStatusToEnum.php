<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDibookingStatusToEnum extends Migration
{
    public function up()
    {
        // Perintah ini hanya MENGUBAH definisi kolom, BUKAN menghapus data.
        $this->forge->modifyColumn('booking', [
            'status' => [
                'type' => 'ENUM("pending","dibooking","disetujui","dibatalkan")',
                'default' => 'pending',
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        // Mengembalikan ke kondisi semula jika migrasi dibatalkan (rollback)
        $this->forge->modifyColumn('booking', [
            'status' => ['type' => 'ENUM("pending","disetujui","dibatalkan")', 'default' => 'pending', 'null' => false],
        ]);
    }
}