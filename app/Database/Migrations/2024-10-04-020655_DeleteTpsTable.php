<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DeleteTpsTable extends Migration
{
    public function up()
    {
        // Hapus tabel tps
        $this->forge->dropTable('tps', true);
    }

    public function down() {}
}
