<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdKecToCpadmin extends Migration
{
    public function up()
    {
        // Menambahkan kolom idkec ke tabel cpadmin
        $this->forge->addColumn('cpadmin', [
            'idkec' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true, // Set null jika tidak selalu diisi
                'after' => 'id' // Ganti dengan nama kolom yang ingin kamu jadikan posisi sebelumnya
            ]
        ]);

        // Menambahkan foreign key
        $this->forge->addForeignKey('idkec', 'kecamatan', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        // Menghapus foreign key dan kolom idkec
        $this->forge->dropForeignKey('cpadmin', 'cpadmin_idkec_foreign');
        $this->forge->dropColumn('cpadmin', 'idkec');
    }
}
