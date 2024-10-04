<?php

namespace App\Models;

use CodeIgniter\Model;

class TpsModel extends Model
{
    protected $table      = 'tps';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_desa', 'nama_tps'];

    // Relasi ke Desa
    public function getWithDesa()
    {
        return $this->select('tps.*, desa.nama_desa')
            ->join('desa', 'tps.id_desa = desa.id')
            ->findAll();
    }
}
