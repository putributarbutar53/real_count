<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTpsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_kec' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_desa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nomor_tps' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jlh_dpt' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        $this->forge->addKey('id', true); // Menambahkan primary key pada kolom id
        $this->forge->createTable('tps'); // Membuat tabel dengan nama 'tps'
    }

    public function down()
    {
        $this->forge->dropTable('tps');
    }
}
