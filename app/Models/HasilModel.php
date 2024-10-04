<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilModel extends Model
{
    protected $table      = 'hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_paslon', 'id_tps', 'suara_sah', 'tidak_sah', 'jlh_suara'];

    // Relasi ke Paslon dan TPS
    public function getWithDetails()
    {
        return $this->select('hasil.*, paslon.nama_paslon, tps.nama_tps')
            ->join('paslon', 'hasil.id_paslon = paslon.id')
            ->join('tps', 'hasil.id_tps = tps.id')
            ->findAll();
    }
}
