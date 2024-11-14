<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHasilProvTable extends Migration
{
    public function up()
    {
        // Membuat tabel 'hasil_prov'
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_paslon'   => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_kec'      => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_desa'     => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'tps'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'suara_sah'    => [
                'type'           => 'INT',
                'constraint'     => 11,
                'default'        => 0,
            ],
            'tidak_sah'    => [
                'type'           => 'INT',
                'constraint'     => 11,
                'default'        => 0,
            ],
            'jlh_suara'    => [
                'type'           => 'INT',
                'constraint'     => 11,
                'default'        => 0,
            ],
            'id_user'      => [
                'type'           => 'INT',
                'constraint'     => 11,
                'default'        => 0,
                'unsigned'       => true,  // Ensure it matches the type of id in cpadmin
            ],
        ]);
        
        // Menambahkan primary key pada kolom 'id'
        $this->forge->addPrimaryKey('id');
        
        // Menambahkan foreign key pada kolom 'id_user' yang mengarah ke kolom 'id' di tabel 'cpadmin'
        $this->forge->addForeignKey('id_user', 'cpadmin', 'id', 'CASCADE', 'CASCADE');
        
        // Membuat tabel 'hasil_prov'
        $this->forge->createTable('hasil_prov');
    }

    public function down()
    {
        // Menghapus tabel 'hasil_prov' jika diperlukan
        $this->forge->dropTable('hasil_prov');
    }
}
