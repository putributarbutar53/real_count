<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
    protected $table      = 'desa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_kec', 'nama_desa'];

    // Relasi ke Kecamatan
    public function getWithKecamatan()
    {
        return $this->select('desa.*, kecamatan.nama_kec')
            ->join('kecamatan', 'desa.id_kec = kecamatan.id')
            ->findAll();
    }
}
