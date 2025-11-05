<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTanggalBayarToDenda extends Migration
{
    public function up()
    {
        // Mendefinisikan kolom baru yang akan ditambahkan
        $fields = [
            'tanggal_bayar' => [
                'type'  => 'DATETIME',
                'null'  => true,      // Penting: agar baris data lama tidak error
                'after' => 'status'   // Opsional: menempatkan kolom ini setelah kolom 'status'
            ],
        ];

        // Perintah untuk menambahkan kolom ke tabel 'denda'
        $this->forge->addColumn('denda', $fields);
    }

    public function down()
    {
        // Perintah untuk menghapus kolom jika migrasi di-rollback (dibatalkan)
        $this->forge->dropColumn('denda', 'tanggal_bayar');
    }
}