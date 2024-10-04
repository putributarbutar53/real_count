<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaslonKecamatanDesaTpsHasilTables extends Migration
{
    public function up()
    {
        // Tabel paslon
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_paslon' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'img' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('paslon');

        // Tabel kecamatan
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kec' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kecamatan');

        // Tabel desa
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
            'nama_desa' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_kec', 'kecamatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('desa');

        // Tabel tps
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_desa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_tps' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_desa', 'desa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tps');

        // Tabel hasil
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_paslon' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_tps' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'suara_sah' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tidak_sah' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'jlh_suara' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_paslon', 'paslon', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_tps', 'tps', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hasil');
    }

    public function down()
    {
        // Hapus tabel dalam urutan yang benar
        $this->forge->dropTable('hasil');
        $this->forge->dropTable('tps');
        $this->forge->dropTable('desa');
        $this->forge->dropTable('kecamatan');
        $this->forge->dropTable('paslon');
    }
}
