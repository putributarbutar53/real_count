<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateHasilTable extends Migration
{
    public function up()
    {
        // Ubah tabel hasil: hapus id_tps, tambahkan id_kec, id_desa, dan tps
        $this->forge->dropForeignKey('hasil', 'hasil_id_tps_foreign');
        $this->forge->dropColumn('hasil', 'id_tps');

        // Tambahkan kolom id_kec, id_desa, dan tps
        $fields = [
            'id_kec' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'id_paslon',
            ],
            'id_desa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'id_kec',
            ],
            'tps' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'after'      => 'id_desa',
            ],
        ];

        $this->forge->addColumn('hasil', $fields);

        // Tambahkan foreign key untuk id_kec dan id_desa
        $this->forge->addForeignKey('id_kec', 'kecamatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_desa', 'desa', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        // Jika ingin rollback perubahan
        $this->forge->dropForeignKey('hasil', 'hasil_id_kec_foreign');
        $this->forge->dropForeignKey('hasil', 'hasil_id_desa_foreign');
        $this->forge->dropColumn('hasil', ['id_kec', 'id_desa', 'tps']);

        $this->forge->addField([
            'id_tps' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addForeignKey('id_tps', 'tps', 'id', 'CASCADE', 'CASCADE');
    }
}
