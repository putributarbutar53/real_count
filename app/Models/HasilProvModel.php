<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilProvModel extends Model
{
    protected $table            = 'hasil_prov';  // Nama tabel
    protected $primaryKey       = 'id';          // Primary key

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_paslon',
        'id_kec',
        'id_desa',
        'tps',
        'suara_sah',
        'tidak_sah',
        'jlh_suara',
        'id_user'
    ];

    // Timestamps
    protected $useTimestamps = false;
}
