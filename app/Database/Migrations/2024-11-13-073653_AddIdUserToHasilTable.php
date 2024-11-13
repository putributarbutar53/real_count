<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdUserToHasilTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('hasil', [
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        // Menambahkan foreign key ke cpadmin (sesuaikan nama primary key di tabel cpadmin jika berbeda)
        $this->forge->addForeignKey(
            'id_user',
            'cpadmin',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        // Hapus foreign key dan kolom
        $this->forge->dropForeignKey('hasil', 'hasil_id_user_foreign');
        $this->forge->dropColumn('hasil', 'id_user');
    }
}
