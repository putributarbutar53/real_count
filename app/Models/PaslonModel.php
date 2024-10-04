<?php

namespace App\Models;

use CodeIgniter\Model;

class PaslonModel extends Model
{
    protected $table      = 'paslon';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_paslon',
        'img'
    ];

    protected $skipValidation = false;
}
