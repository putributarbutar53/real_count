<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilModel extends Model
{
    protected $table      = 'hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_paslon', 'id_kec', 'id_desa', 'tps', 'suara_sah', 'tidak_sah', 'jlh_suara'];

    public function getWithDetails()
    {
        return $this->select('hasil.*, paslon.nama_paslon, kecamatan.nama_kec, desa.nama_desa, hasil.tps')
            ->join('paslon', 'hasil.id_paslon = paslon.id')
            ->join('kecamatan', 'hasil.id_kec = kecamatan.id')
            ->join('desa', 'hasil.id_desa = desa.id')
            ->findAll();
    }

    
}
